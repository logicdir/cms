<?php

namespace App\Modules\Media\Services;

use App\Modules\Media\Jobs\GenerateThumbnails;
use App\Modules\Media\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaUploader
{
    protected string $disk;

    public function __construct()
    {
        $this->disk = config('media.disk', 'public');
    }

    /**
     * Upload a single file.
     */
    public function upload(UploadedFile $file, ?int $folderId = null): Media
    {
        // 1. Security: Validate Magic Bytes (Basic)
        $this->validateMagicBytes($file);

        // 2. Security: Sanitize SVG
        if ($file->getMimeType() === 'image/svg+xml') {
            $this->sanitizeSvg($file);
        }

        $originalName = $file->getClientOriginalName();
        $fileName = $this->generateFileName($file);
        $path = $this->generatePath($fileName);

        // Store file
        Storage::disk($this->disk)->putFileAs(
            dirname($path),
            $file,
            basename($path)
        );

        // Get image dimensions if applicable
        $dimensions = $this->getImageDimensions($file);

        // Create media record
        $media = Media::create([
            'folder_id' => $folderId,
            'name' => $originalName,
            'file_name' => $fileName,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'width' => $dimensions['width'] ?? null,
            'height' => $dimensions['height'] ?? null,
            'disk' => $this->disk,
            'path' => $path,
            'created_by' => auth()->id() ?? 1,
        ]);

        // Dispatch thumbnail generation if image
        if (str_contains($media->mime_type, 'image') && $media->mime_type !== 'image/svg+xml') {
            GenerateThumbnails::dispatch($media);
        }

        return $media;
    }

    /**
     * Simple SVG Sanitization to remove scripts and event handlers.
     */
    protected function sanitizeSvg(UploadedFile $file): void
    {
        $content = file_get_contents($file->getRealPath());
        
        // Remove <script> tags
        $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $content);
        
        // Remove event handlers (onmouseover, onclick, etc.)
        $content = preg_replace('/on\w+="[^"]*"/i', '', $content);
        $content = preg_replace('/on\w+=\'[^\']*\'/i', '', $content);

        file_put_contents($file->getRealPath(), $content);
    }

    /**
     * Validate file header matches expected mime.
     */
    protected function validateMagicBytes(UploadedFile $file): void
    {
        // In a real production environment, we'd use a library or finer checks.
        // For now, we trust Laravel's getMimeType which uses finfo.
        if (str_contains($file->getMimeType(), 'text/php') || str_contains($file->getMimeType(), 'application/x-php')) {
            throw new \Exception('Invalid file content detected.');
        }
    }

    /**
     * Handle chunked upload.
     */
    public function handleChunk(array $data, ?int $folderId = null): ?Media
    {
        $file = $data['file'];
        $chunkIndex = (int) $data['chunk_index'];
        $totalChunks = (int) $data['total_chunks'];
        $identifier = $data['identifier'];

        $tempPath = "chunks/{$identifier}";
        $chunkName = "chunk_{$chunkIndex}";

        Storage::disk('local')->putFileAs($tempPath, $file, $chunkName);

        if ($chunkIndex === $totalChunks - 1) {
            return $this->assembleChunks($identifier, $data['original_name'], $folderId);
        }

        return null;
    }

    /**
     * Assemble chunks into a final file.
     */
    protected function assembleChunks(string $identifier, string $originalName, ?int $folderId): Media
    {
        $tempPath = storage_path("app/chunks/{$identifier}");
        $finalTempFile = storage_path("app/chunks/{$identifier}_final");

        $out = fopen($finalTempFile, 'wb');
        
        // Find all chunks and sort them
        $chunks = glob("{$tempPath}/chunk_*");
        natsort($chunks);

        foreach ($chunks as $chunk) {
            $in = fopen($chunk, 'rb');
            stream_copy_to_stream($in, $out);
            fclose($in);
            @unlink($chunk);
        }

        fclose($out);
        @rmdir($tempPath);

        // Create UploadedFile from temp file for validation/processing
        $file = new UploadedFile($finalTempFile, $originalName, null, null, true);
        
        $media = $this->upload($file, $folderId);

        @unlink($finalTempFile);

        return $media;
    }

    protected function generateFileName(UploadedFile $file): string
    {
        return Str::uuid() . '.' . $file->getClientOriginalExtension();
    }

    protected function generatePath(string $fileName): string
    {
        $date = now()->format('Y/m/d');
        return "uploads/{$date}/{$fileName}";
    }

    protected function getImageDimensions(UploadedFile $file): array
    {
        if (!str_contains($file->getMimeType(), 'image')) {
            return [];
        }

        try {
            $size = getimagesize($file->getRealPath());
            return [
                'width' => $size[0],
                'height' => $size[1]
            ];
        } catch (\Throwable $e) {
            return [];
        }
    }
}
