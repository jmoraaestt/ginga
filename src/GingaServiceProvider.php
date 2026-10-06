<?php

namespace Ginga;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class GingaServiceProvider extends ServiceProvider 
{
    public function boot(): variant_mod{
        Blade::anonymousComponentPath(__DIR__ . '/../resources/views/components', 'ginga');
    }
}