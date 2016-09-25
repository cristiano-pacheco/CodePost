<?php

namespace CodePress\CodePost\Providers;

use Illuminate\Support\ServiceProvider;

class CodeCategoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../resources/migrations/' => base_path('database/migrations')
        ], 'migrations');

        $this->loadViewsFrom(__DIR__.'/../../resources/views/codepost', 'codepost');

        require __DIR__ .'/../../routes.php';
    }

    public function register()
    {

    }
}