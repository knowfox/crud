<?php

namespace Knowfox\Crud;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Support\Facades\View;
use Knowfox\Crud\ViewComposers\SelectFieldComposer;

class ServiceProvider extends IlluminateServiceProvider
{
    public function boot()
    {
        View::composer(
            'crud::fields.select', SelectFieldComposer::class
        );

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        $this->loadViewsFrom(__DIR__ . '/../views', 'crud');
        $this->loadRoutesFrom(__DIR__ . '/../routes.php');
        $this->loadTranslationsFrom(__DIR__ . '/../translations', 'crud');
    }

    public function register()
    {
    }
}
