<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use App\Services\ImageCompressionService;

class ImageHelper
{
    /**
     * Get optimized image URL with WebP support
     */
    public static function optimized(string $webpPath, string $jpegPath = null): string
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
     * Generate responsive image HTML with WebP support
     */
    public static function responsive(string $webpPath, string $jpegPath = null, string $alt = '', array $attributes = []): string
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

    /**
     * Get thumbnail URL with fallback
     */
    public static function thumbnail(string $originalPath, string $size = 'medium'): string
    {
        $thumbnailPath = str_replace('.webp', "_{$size}.webp", $originalPath);
        $jpegThumbnailPath = str_replace('.webp', "_{$size}.jpg", $originalPath);

        if (Storage::exists($thumbnailPath)) {
            return self::optimized($thumbnailPath, $jpegThumbnailPath);
        }

        // Fallback to original
        return self::optimized($originalPath);
    }

    /**
     * Get image dimensions
     */
    public static function dimensions(string $path): array
    {
        if (!Storage::exists($path)) {
            return ['width' => 0, 'height' => 0];
        }

        try {
            $imageInfo = getimagesize(Storage::path($path));
            return [
                'width' => $imageInfo[0] ?? 0,
                'height' => $imageInfo[1] ?? 0
            ];
        } catch (\Exception $e) {
            return ['width' => 0, 'height' => 0];
        }
    }

    /**
     * Get image file size in human readable format
     */
    public static function fileSize(string $path): string
    {
        if (!Storage::exists($path)) {
            return '0 B';
        }

        $bytes = Storage::size($path);
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if image is WebP format
     */
    public static function isWebP(string $path): bool
    {
        return str_ends_with(strtolower($path), '.webp');
    }

    /**
     * Get image format
     */
    public static function format(string $path): string
    {
        return strtolower(pathinfo($path, PATHINFO_EXTENSION));
    }

    /**
     * Generate image placeholder
     */
    public static function placeholder(int $width = 300, int $height = 200, string $text = 'Image'): string
    {
        $color = 'f0f2f5';
        $textColor = '666666';

        return "data:image/svg+xml;base64," . base64_encode("
            <svg width=\"{$width}\" height=\"{$height}\" xmlns=\"http://www.w3.org/2000/svg\">
                <rect width=\"100%\" height=\"100%\" fill=\"#{$color}\"/>
                <text x=\"50%\" y=\"50%\" font-family=\"Arial, sans-serif\" font-size=\"14\"
                      fill=\"#{$textColor}\" text-anchor=\"middle\" dy=\".3em\">{$text}</text>
            </svg>
        ");
    }

    /**
     * Get compression statistics
     */
    public static function compressionStats(string $webpPath, int $originalSize = 0): array
    {
        if (!Storage::exists($webpPath)) {
            return [];
        }

        $compressedSize = Storage::size($webpPath);

        if ($originalSize === 0) {
            return [
                'compressed_size' => $compressedSize,
                'compressed_size_human' => self::fileSize($webpPath),
                'compression_ratio' => 0
            ];
        }

        $compressionRatio = round((1 - $compressedSize / $originalSize) * 100, 2);

        return [
            'original_size' => $originalSize,
            'original_size_human' => self::formatFileSize($originalSize),
            'compressed_size' => $compressedSize,
            'compressed_size_human' => self::fileSize($webpPath),
            'compression_ratio' => $compressionRatio,
            'space_saved' => $originalSize - $compressedSize,
            'space_saved_human' => self::formatFileSize($originalSize - $compressedSize)
        ];
    }

    /**
     * Format file size helper
     */
    private static function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
