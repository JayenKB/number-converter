<?php

namespace Jayen\NumberConverter;

use Illuminate\Support\ServiceProvider;

class NumberConverterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind helper class to container
        $this->app->singleton('number-converter', function () {
            return new NumberConverter;
        });
    }

    public function boot(): void
    {
        // If needed, you can publish configs here later
    }
}
