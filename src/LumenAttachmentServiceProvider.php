<?php

namespace mradang\LumenCustomField;

use Illuminate\Support\ServiceProvider;

class LumenCustomFieldServiceProvider extends ServiceProvider {

    public function boot() {
        $this->registerMigrations();
    }

    protected function registerMigrations() {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/migrations');
        }
    }

}