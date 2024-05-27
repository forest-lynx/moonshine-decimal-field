<?php

declare(strict_types=1);

namespace ForestLynx\MoonShine\Providers;

use Illuminate\Support\ServiceProvider;

final class DecimalFieldServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'moonshine-fl');
        $this->publishes([
            __DIR__ . '/../../public' => public_path('vendor/moonshine-decimal-field'),
        ], ['moonshine-decimal-field', 'laravel-assets']);

        moonshineAssets()->add([
            '/vendor/moonshine-decimal-field/css/decimal-field.css',
         ]);
    }
}
