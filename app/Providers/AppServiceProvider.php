<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\FormatHelper;
use Midtrans\Config as MidtransConfig;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('midtrans.server_key')) {
            MidtransConfig::$serverKey = config('midtrans.server_key');
            MidtransConfig::$isProduction = (bool) config('midtrans.is_production');
            MidtransConfig::$isSanitized = (bool) config('midtrans.is_sanitized');
            MidtransConfig::$is3ds = (bool) config('midtrans.is_3ds');
        }

        // Register Blade directives untuk format harga
        Blade::directive('rupiah', function ($amount) {
            return "<?php echo FormatHelper::rupiah({$amount}); ?>";
        });

        Blade::directive('rupiahNumber', function ($amount) {
            return "<?php echo FormatHelper::rupiahNumber({$amount}); ?>";
        });

        Blade::directive('percent', function ($value) {
            return "<?php echo FormatHelper::percent({$value}); ?>";
        });

        Blade::directive('number', function ($value) {
            return "<?php echo FormatHelper::number({$value}); ?>";
        });
    }
}
