<?php

namespace App\Providers;
use Illuminate\Support\Facades\Schema;
use App\Models\Wishlist;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Models\Translation;
use App;
use Cache;
use Log;
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
    // public function boot(): void
    // {
    //     Paginator::useBootstrapFive(); 
    //     Schema::defaultStringLength(191);

    //     // $translations = Translation::all()->groupBy('language');
    //     $translations = Cache::rememberForever('translations', function () {
    //         return Translation::all()->groupBy('language');
    //     });
    //     foreach ($translations as $language => $items) {
    //         foreach ($items as $item) {
    //             App::setLocale($language);
    //             // trans()->set($item->key, $item->value);
    //             trans()->addLines([$item->key => $item->value], $language);
    //         }
    //     }
    // }
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Schema::defaultStringLength(191);

        // Cache translations for better performance
        
    }

}
