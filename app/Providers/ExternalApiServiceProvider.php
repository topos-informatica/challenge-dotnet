<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ExternalApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ExternalApiService::class, function ($app) {
            return new ExternalApiService(
                $app->make(CommentModerationInterface::class),
                $app->make(ReportCreationService::class)  
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
