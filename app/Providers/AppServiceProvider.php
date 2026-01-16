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
        // Configure Language Switch if the package is installed
        if (class_exists(\BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch::class)) {
            \BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch::configureUsing(function ($switch) {
                $switch
                    ->locales(['en', 'fr', 'ar'])
                    ->labels([
                        'en' => 'English',
                        'fr' => 'Français',
                        'ar' => 'العربية',
                    ])
                    ->flags([
                        'en' => asset('flags/en.svg'),
                        'fr' => asset('flags/fr.svg'),
                        'ar' => asset('flags/ar.svg'),
                    ])
                    ->visible(insidePanels: true)
                    ->circular();
            });
        }
    }
}
