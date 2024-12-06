<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DatabaseTranslationLoader;
use Illuminate\Translation\Translator;
class DatabaseTranslationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new DatabaseTranslationLoader();
        });

        $this->app->extend('translator', function ($translator, $app) {
            return new Translator($app['translation.loader'], $app['config']['app.locale']);
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
