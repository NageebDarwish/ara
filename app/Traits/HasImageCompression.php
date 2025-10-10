<?php

namespace App\Traits;

use App\Services\ImageCompressionService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasImageCompression
{
    protected $imageCompressionService;

    /**
     * Initialize image compression service
     */
    protected function getImageCompressionService(): ImageCompressionService
    {
        if (!$this->imageCompressionService) {
            $this->imageCompressionService = app(ImageCompressionService::class);
        }

        return $this->imageCompressionService;
    }

    /**
     * Compress and store profile image
     */
    protected function compressProfileImage(UploadedFile $file): array
    {
        return $this->getImageCompressionService()->compressAndStore($file, 'profile');
    }

    /**
     * Compress and store blog image
     */
    protected function compressBlogImage(UploadedFile $file): array
    {
        return $this->getImageCompressionService()->compressAndStore($file, 'blog');
    }

    /**
     * Compress and store thumbnail image
     */
    protected function compressThumbnailImage(UploadedFile $file): array
    {
        return $this->getImageCompressionService()->compressAndStore($file, 'thumbnail');
    }

    /**
     * Compress and store general image
     */
    protected function compressGeneralImage(UploadedFile $file): array
    {
        return $this->getImageCompressionService()->compressAndStore($file, 'general');
    }

    /**
     * Get optimized image URL
     */
    protected function getOptimizedImageUrl(string $webpPath, string $jpegPath = null): string
    {
        return $this->getImageCompressionService()->getOptimizedUrl($webpPath, $jpegPath);
    }

    /**
     * Delete image and all variants
     */
    protected function deleteImageVariants(string $originalPath): bool
    {
        return $this->getImageCompressionService()->deleteImageVariants($originalPath);
    }

    /**
     * Get responsive image HTML with WebP support
     */
    protected function getResponsiveImageHtml(string $webpPath, string $jpegPath = null, string $alt = '', array $attributes = []): string
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
    protected function getThumbnailUrl(string $originalPath, string $size = 'medium'): string
    {
        $thumbnailPath = str_replace('.webp', "_{$size}.webp", $originalPath);
        $jpegThumbnailPath = str_replace('.webp', "_{$size}.jpg", $originalPath);

        if (Storage::exists($thumbnailPath)) {
            return $this->getOptimizedImageUrl($thumbnailPath, $jpegThumbnailPath);
        }

        // Fallback to original
        return $this->getOptimizedImageUrl($originalPath);
    }
}
