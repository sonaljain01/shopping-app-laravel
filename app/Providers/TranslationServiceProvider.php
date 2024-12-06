<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\Translator;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    //     $this->app->extend('translator', function ($translator, $app) {
    //         $translator->addLoader('database', function ($locale, $group, $namespace = null) {
    //             return \App\Models\Translation::where('language', $locale)
    //                 ->pluck('value', 'key')
    //                 ->toArray();
    //         });
    //         return $translator;
    //     });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
