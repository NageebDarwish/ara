<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImageCompressionService;
use Illuminate\Support\Facades\Storage;

class TestImageCompression extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:test {--type=blog : Image type (blog, profile, thumbnail, general)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test image compression service';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');

        $this->info("Testing image compression for type: {$type}");
        $this->newLine();

        // Check if storage link exists
        if (!Storage::exists('uploads/test')) {
            $this->info('Creating test directory...');
            Storage::makeDirectory('uploads/test');
        }

        // Test with a sample image (you can add a test image to storage/app/public/test.jpg)
        $testImagePath = storage_path('app/public/test.jpg');

        if (!file_exists($testImagePath)) {
            $this->error('Test image not found. Please add a test image at: storage/app/public/test.jpg');
            return 1;
        }

        try {
            $service = app(ImageCompressionService::class);

            // Create a mock UploadedFile
            $file = new \Illuminate\Http\UploadedFile(
                $testImagePath,
                'test.jpg',
                'image/jpeg',
                null,
                true
            );

            $this->info('Compressing image...');
            $result = $service->compressAndStore($file, $type);

            $this->info('✅ Compression completed successfully!');
            $this->newLine();

            $this->table(
                ['Property', 'Value'],
                [
                    ['Original Size', $this->formatBytes($result['original_size'])],
                    ['Compressed Size', $this->formatBytes($result['size'])],
                    ['Compression Ratio', $result['compression_ratio'] . '%'],
                    ['WebP Path', $result['original_webp']],
                    ['JPEG Path', $result['original_jpeg']],
                    ['Filename', $result['filename']],
                ]
            );

            if (!empty($result['thumbnails'])) {
                $this->info('📸 Generated thumbnails:');
                foreach ($result['thumbnails'] as $size => $paths) {
                    $this->line("  - {$size}: {$paths['webp']} (WebP), {$paths['jpeg']} (JPEG)");
                }
            }

            // Test optimized URL
            $optimizedUrl = $service->getOptimizedUrl($result['original_webp'], $result['original_jpeg']);
            $this->newLine();
            $this->info("🔗 Optimized URL: {$optimizedUrl}");

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Compression failed: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
