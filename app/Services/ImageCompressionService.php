<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageCompressionService
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Compress and optimize image based on type
     */
    public function compressAndStore(UploadedFile $file, string $type = 'general', string $disk = 'public'): array
    {
        $config = $this->getCompressionConfig($type);

        // Generate unique filename
        $filename = $this->generateFilename($file, $config['format']);
        $originalPath = $type . '/' . $filename;

        // Process the image
        $image = $this->manager->read($file->getPathname());

        // Resize if needed
        if ($config['max_width'] || $config['max_height']) {
            $image = $this->resizeImage($image, $config['max_width'], $config['max_height']);
        }

        // Optimize quality and store WebP
        $webpPath = $originalPath;
        $webpEncoded = $image->toWebp($config['quality']);
        Storage::disk($disk)->put($webpPath, $webpEncoded);

        // Create JPEG fallback for older browsers
        $jpegPath = str_replace('.webp', '.jpg', $originalPath);
        $jpegEncoded = $image->toJpeg($config['quality']);
        Storage::disk($disk)->put($jpegPath, $jpegEncoded);

        // Generate thumbnails if needed
        $thumbnails = [];
        if ($config['generate_thumbnails']) {
            $thumbnails = $this->generateThumbnails($image, $originalPath, $disk, $config['quality']);
        }

        return [
            'original_webp' => $originalPath, // Return without 'uploads/' prefix
            'original_jpeg' => str_replace('.webp', '.jpg', $originalPath),
            'thumbnails' => $thumbnails,
            'filename' => $filename,
            'size' => Storage::disk($disk)->size($webpPath),
            'original_size' => $file->getSize(),
            'compression_ratio' => round((1 - Storage::disk($disk)->size($webpPath) / $file->getSize()) * 100, 2)
        ];
    }

    /**
     * Get compression configuration based on image type
     */
    private function getCompressionConfig(string $type): array
    {
        $configs = [
            'profile' => [
                'quality' => 85,
                'max_width' => 512,
                'max_height' => 512,
                'format' => 'webp',
                'generate_thumbnails' => true
            ],
            'blog' => [
                'quality' => 80,
                'max_width' => 1200,
                'max_height' => null,
                'format' => 'webp',
                'generate_thumbnails' => true
            ],
            'thumbnail' => [
                'quality' => 75,
                'max_width' => 1280,
                'max_height' => 720,
                'format' => 'webp',
                'generate_thumbnails' => true
            ],
            'general' => [
                'quality' => 80,
                'max_width' => 1920,
                'max_height' => null,
                'format' => 'webp',
                'generate_thumbnails' => false
            ]
        ];

        return $configs[$type] ?? $configs['general'];
    }

    /**
     * Resize image maintaining aspect ratio
     */
    private function resizeImage($image, ?int $maxWidth, ?int $maxHeight)
    {
        $width = $image->width();
        $height = $image->height();

        // Calculate new dimensions
        if ($maxWidth && $width > $maxWidth) {
            $height = ($height * $maxWidth) / $width;
            $width = $maxWidth;
        }

        if ($maxHeight && $height > $maxHeight) {
            $width = ($width * $maxHeight) / $height;
            $height = $maxHeight;
        }

        if ($width !== $image->width() || $height !== $image->height()) {
            return $image->resize($width, $height);
        }

        return $image;
    }

    /**
     * Generate thumbnails in different sizes
     */
    private function generateThumbnails($image, string $originalPath, string $disk, int $quality): array
    {
        $thumbnails = [];
        $sizes = [
            'small' => 150,
            'medium' => 300,
            'large' => 600
        ];

        foreach ($sizes as $sizeName => $size) {
            $thumbnail = $image->resize($size, $size);
            $thumbnailPath = str_replace('.webp', "_{$sizeName}.webp", $originalPath);
            $jpegThumbnailPath = str_replace('.webp', "_{$sizeName}.jpg", $originalPath);

            // Store WebP thumbnail
            $webpThumbnailEncoded = $thumbnail->toWebp($quality);
            Storage::disk($disk)->put($thumbnailPath, $webpThumbnailEncoded);

            // Store JPEG thumbnail
            $jpegThumbnailEncoded = $thumbnail->toJpeg($quality);
            Storage::disk($disk)->put($jpegThumbnailPath, $jpegThumbnailEncoded);

            $thumbnails[$sizeName] = [
                'webp' => $thumbnailPath,
                'jpeg' => $jpegThumbnailPath
            ];
        }

        return $thumbnails;
    }

    /**
     * Generate unique filename
     */
    private function generateFilename(UploadedFile $file, string $format): string
    {
        $extension = $format === 'webp' ? 'webp' : 'jpg';
        return Str::uuid() . '.' . $extension;
    }

    /**
     * Get optimized image URL with WebP support
     */
    public function getOptimizedUrl(string $webpPath, string $jpegPath = null): string
    {
        if (!$jpegPath) {
            $jpegPath = str_replace('.webp', '.jpg', $webpPath);
        }

        // Check if WebP is supported by browser
        if (request()->header('Accept') && str_contains(request()->header('Accept'), 'image/webp')) {
            return Storage::url($webpPath);
        }

        return Storage::url($jpegPath);
    }

    /**
     * Delete image and all its variants
     */
    public function deleteImageVariants(string $originalPath, string $disk = 'public'): bool
    {
        $variants = [
            $originalPath, // Original WebP
            str_replace('.webp', '.jpg', $originalPath), // JPEG fallback
            str_replace('.webp', '_small.webp', $originalPath), // Small thumbnail WebP
            str_replace('.webp', '_small.jpg', $originalPath), // Small thumbnail JPEG
            str_replace('.webp', '_medium.webp', $originalPath), // Medium thumbnail WebP
            str_replace('.webp', '_medium.jpg', $originalPath), // Medium thumbnail JPEG
            str_replace('.webp', '_large.webp', $originalPath), // Large thumbnail WebP
            str_replace('.webp', '_large.jpg', $originalPath), // Large thumbnail JPEG
        ];

        $deleted = true;
        foreach ($variants as $variant) {
            if (Storage::disk($disk)->exists($variant)) {
                $deleted = $deleted && Storage::disk($disk)->delete($variant);
            }
        }

        return $deleted;
    }

    /**
     * Batch compress multiple images
     */
    public function batchCompress(array $files, string $type = 'general', string $disk = 'public'): array
    {
        $results = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $results[] = $this->compressAndStore($file, $type, $disk);
            }
        }

        return $results;
    }

    /**
     * Get image statistics
     */
    public function getImageStats(string $path, string $disk = 'public'): array
    {
        if (!Storage::disk($disk)->exists($path)) {
            return [];
        }

        $image = $this->manager->read(Storage::disk($disk)->path($path));

        return [
            'width' => $image->width(),
            'height' => $image->height(),
            'size' => Storage::disk($disk)->size($path),
            'format' => pathinfo($path, PATHINFO_EXTENSION),
            'mime_type' => mime_content_type(Storage::disk($disk)->path($path))
        ];
    }
}
