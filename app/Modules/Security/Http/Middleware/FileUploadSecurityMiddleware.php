<?php

namespace App\Modules\Security\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileUploadSecurityMiddleware
{
    protected $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
        'application/pdf',
        'video/mp4',
    ];

    public function handle(Request $request, Closure $next)
    {
        foreach ($request->allFiles() as $file) {
            // Check magic bytes / MIME type
            if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
                throw new FileException('Unauthorized file type detected.');
            }

            // Check for potential PHP code in images
            $content = file_get_contents($file->getRealPath());
            if (preg_match('/<\?php|eval\(|base64_decode\(/i', $content)) {
                throw new FileException('Malicious content detected in file.');
            }

            // Size limit check (10MB)
            if ($file->getSize() > 10 * 1024 * 1024) {
                throw new FileException('File size exceeds limit (10MB).');
            }
        }

        return $next($request);
    }
}
