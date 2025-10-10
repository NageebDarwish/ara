<?php

namespace App\Helpers;

use App\Services\ImageCompressionService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UploadFiles
{
    protected static $compressionService;

    /**
     * Get image compression service instance
     */
    protected static function getCompressionService(): ImageCompressionService
    {
        if (!self::$compressionService) {
            self::$compressionService = app(ImageCompressionService::class);
        }

        return self::$compressionService;
    }

    /**
     * Upload and compress image
     */
    public static function upload($file, string $folder, bool $compress = true)
    {
        if (!$file) {
            return null;
        }

        // Check if it's an image and compression is enabled
        if ($compress && self::isImage($file)) {
            return self::uploadWithCompression($file, $folder);
        }

        // Fallback to original upload method for non-images or when compression is disabled
        return self::uploadOriginal($file, $folder);
    }

    /**
     * Upload image with compression
     */
    protected static function uploadWithCompression($file, string $folder): ?string
    {
        try {
            // Determine compression type based on folder
            $compressionType = self::getCompressionType($folder);

            // Compress and store the image
            $result = self::getCompressionService()->compressAndStore($file, $compressionType);

            // Return the WebP path for modern browsers
            return Storage::url($result['original_webp']);

        } catch (\Exception $e) {
            // Log error and fallback to original upload
            Log::error('Image compression failed: ' . $e->getMessage());
            return self::uploadOriginal($file, $folder);
        }
    }

    /**
     * Original upload method (fallback)
     */
    protected static function uploadOriginal($file, string $folder): string
    {
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $folderPath = public_path($folder);

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        $file->move($folderPath, $fileName);
        $baseUrl = url('/');
        $fileUrl = $baseUrl . '/' . $folder . '/' . $fileName;

        return $fileUrl;
    }

    /**
     * Delete uploaded file
     */
    public static function delete($file, $folder)
    {
        if (!$file) {
            return;
        }

        // Try to delete compressed variants first
        if (self::isCompressedImage($file)) {
            try {
                // Extract the storage path from the URL
                $path = str_replace(Storage::url(''), '', $file);
                self::getCompressionService()->deleteImageVariants($path);
                return;
            } catch (\Exception $e) {
                Log::error('Failed to delete compressed image variants: ' . $e->getMessage());
            }
        }

        // Fallback to original delete method
        $filePath = public_path($folder . '/' . basename($file));
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    /**
     * Check if file is an image
     */
    protected static function isImage($file): bool
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
        $extension = strtolower($file->getClientOriginalExtension());

        return in_array($extension, $imageExtensions);
    }

    /**
     * Check if file is a compressed image
     */
    protected static function isCompressedImage($file): bool
    {
        return str_contains($file, '/storage/') && str_contains($file, '.webp');
    }

    /**
     * Get compression type based on folder
     */
    protected static function getCompressionType(string $folder): string
    {
        $typeMapping = [
            'blog' => 'blog',
            'series' => 'thumbnail',
            'plan' => 'general',
            'profile' => 'profile',
            'user' => 'profile',
            'avatar' => 'profile',
            'thumbnail' => 'thumbnail',
        ];

        return $typeMapping[$folder] ?? 'general';
    }

    /**
     * Get optimized image URL with WebP support
     */
    public static function getOptimizedUrl($webpPath, $jpegPath = null): string
    {
        if (!$jpegPath) {
            $jpegPath = str_replace('.webp', '.jpg', $webpPath);
        }

        return self::getCompressionService()->getOptimizedUrl($webpPath, $jpegPath);
    }

    /**
     * Get responsive image HTML
     */
    public static function getResponsiveImageHtml($webpPath, $jpegPath = null, $alt = '', $attributes = []): string
    {
        if (!$jpegPath) {
            $jpegPath = str_replace('.webp', '.jpg', $webpPath);
        }

        $webpUrl = Storage::url($webpPath);
        $jpegUrl = Storage::url($jpegPath);

        $attributesString = '';
        foreach ($attributes as $key => $value) {
            $attributesString .= " {$key}=\"{$value}\"";
        }

        return "
            <picture{$attributesString}>
                <source srcset=\"{$webpUrl}\" type=\"image/webp\">
                <img src=\"{$jpegUrl}\" alt=\"{$alt}\" loading=\"lazy\">
            </picture>
        ";
    }
}
