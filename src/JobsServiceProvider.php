<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/3/19
 */

namespace Limen\Laravel\Jobs;

use Illuminate\Support\ServiceProvider;
use Limen\Laravel\Jobs\Console\InstallCommand;

class JobsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            InstallCommand::class
        ]);
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config' => config_path()], 'laravel-jobs-config');
            $this->publishes([__DIR__.'/../database' => database_path()], 'laravel-jobs-database');
        }
    }
}