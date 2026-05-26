<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // 1. Force HTTPS in production
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // 2. Auto-initialize SQLite database if connection is sqlite
        if (config('database.default') === 'sqlite') {
            $dbPath = config('database.connections.sqlite.database');
            if ($dbPath && $dbPath !== ':memory:') {
                $dbDir = dirname($dbPath);
                if (!is_dir($dbDir)) {
                    mkdir($dbDir, 0755, true);
                }
                
                $dbCreatedOrEmpty = false;
                if (!file_exists($dbPath)) {
                    touch($dbPath);
                    @chmod($dbPath, 0666);
                    $dbCreatedOrEmpty = true;
                } elseif (filesize($dbPath) === 0) {
                    $dbCreatedOrEmpty = true;
                }

                if ($dbCreatedOrEmpty) {
                    try {
                        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('Auto-migration/seeding failed: ' . $e->getMessage());
                    }
                }
            }
        }

        // 3. Ensure storage link exists
        if (!file_exists(public_path('storage'))) {
            try {
                \Illuminate\Support\Facades\Artisan::call('storage:link');
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Auto-storage:link failed: ' . $e->getMessage());
            }
        }
    }
}
