<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
    public function boot()
    {
        // Force HTTPS for all URLs when in production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Set SQL mode for MySQL connections only
        try {
            if (config('database.default') === 'mysql') {
                DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
            }
        } catch (\Exception $e) {
            // Log the error but don't break the application
            Log::error('Failed to set SQL mode: ' . $e->getMessage());
        }
    }
}
