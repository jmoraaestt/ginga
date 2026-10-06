<?php

namespace Ginga;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class GingaServiceProvider extends ServiceProvider 
{
    public function boot(): void{
        Blade::anonymousComponentPath(__DIR__ . '/../resources/views/components', 'ginga');

        $this->publishes([
    __DIR__ . '/../resources/css/ginga-theme.css' => public_path('vendor/ginga/ginga-theme.css'),
    ], 'ginga-assets');
    }
}