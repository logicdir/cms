<?php

namespace App\Modules\Media\Services;

use App\Modules\Media\Models\Media;
use App\Modules\Media\Models\MediaFolder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class MediaLibrary
{
    /**
     * Get files and folders for a specific folder.
     */
    public function getContent(?int $folderId = null, array $filters = []): array
    {
        $folders = MediaFolder::where('parent_id', $folderId)
            ->withCount('media')
            ->orderBy('name')
            ->get();

        $query = Media::where('folder_id', $folderId);

        if (!empty($filters['type'])) {
            $query->where('mime_type', 'like', "{$filters['type']}/%");
        }

        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%");
        }

        $media = $query->latest()->get();

        return [
            'folders' => $folders,
            'media' => $media
        ];
    }

    /**
     * Move items to a new folder.
     */
    public function moveItems(array $fileIds, array $folderIds, ?int $targetFolderId): void
    {
        if (!empty($fileIds)) {
            Media::whereIn('id', $fileIds)->update(['folder_id' => $targetFolderId]);
        }

        if (!empty($folderIds)) {
            MediaFolder::whereIn('id', $folderIds)->update(['parent_id' => $targetFolderId]);
        }
    }

    /**
     * Delete files and their variants.
     */
    public function deleteFiles(array $ids): void
    {
        $files = Media::whereIn('id', $ids)->get();

        foreach ($files as $file) {
            $this->deleteFilePhysical($file);
            $file->delete();
        }
    }

    /**
     * Physical deletion of file and its variants.
     */
    protected function deleteFilePhysical(Media $media): void
    {
        $disk = Storage::disk($media->disk);
        
        // Delete original
        $disk->delete($media->path);

        // Delete variants (assuming .webp variants)
        $pathWithoutExt = substr($media->path, 0, strrpos($media->path, '.'));
        $variants = ['thumbnail', 'medium', 'large', 'hero'];

        foreach ($variants as $variant) {
            $disk->delete("{$pathWithoutExt}_{$variant}.webp");
        }
    }
}
