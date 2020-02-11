<?php

namespace mradang\LaravelCustomField;

use Illuminate\Support\ServiceProvider;

class LaravelCustomFieldServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(realpath(__DIR__.'/../migrations/'));
        }
    }
}
