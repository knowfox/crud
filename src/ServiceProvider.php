<?php

namespace Knowfox\Crud;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Support\Facades\View;
use Knowfox\Crud\ViewComposers\SelectFieldComposer;
use Knowfox\Crud\ViewComposers\TagsFieldComposer;
use Knowfox\Crud\Models\Setting;
use Knowfox\Crud\Models\ConfigSetting;

class ServiceProvider extends IlluminateServiceProvider
{
    public function boot()
    {
        $theme = config('crud.theme');

        View::composer(
            'crud::' . $theme . '.fields.select', SelectFieldComposer::class
        );

        View::composer(
            'crud::' . $theme . '.fields.tags', TagsFieldComposer::class
        );

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        $this->loadViewsFrom(__DIR__ . '/../views', 'crud');
        $this->loadRoutesFrom(__DIR__ . '/../routes.php');
        $this->loadTranslationsFrom(__DIR__ . '/../translations', 'crud');

        $this->publishes([
            __DIR__ . '/../crud.php' => config_path('crud.php'),
        ], 'config');
    }

    public function register()
    {
        $setting_class = config('crud.setting_class', '\\Knowfox\\Crud\\Models\\ConfigSetting');
        $this->app->bind(
            Setting::class,
            $setting_class
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../setting.php', 'crud.setting'
        );
    }
}
