<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\ImageHelper;

class ImageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register Blade directives for image optimization
        Blade::directive('optimizedImage', function ($expression) {
            return "<?php echo App\Helpers\ImageHelper::optimized($expression); ?>";
        });

        Blade::directive('responsiveImage', function ($expression) {
            return "<?php echo App\Helpers\ImageHelper::responsive($expression); ?>";
        });

        Blade::directive('thumbnail', function ($expression) {
            return "<?php echo App\Helpers\ImageHelper::thumbnail($expression); ?>";
        });

        Blade::directive('imagePlaceholder', function ($expression) {
            return "<?php echo App\Helpers\ImageHelper::placeholder($expression); ?>";
        });
    }
}
