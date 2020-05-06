<?php

namespace Marshmallow\MultiLanguage;

use Illuminate\Support\ServiceProvider;
use Marshmallow\MultiLanguage\Console\Commands\MigrateTableCommand;

class MultiLanguageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        
        if ($this->app->runningInConsole()) {
            $this->commands([
                MigrateTableCommand::class,
            ]);
        }
    }
}
