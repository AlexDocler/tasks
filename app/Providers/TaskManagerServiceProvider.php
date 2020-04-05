<?php

namespace App\Providers;

use App\Repository\LaravelTaskRepository;
use Illuminate\Support\ServiceProvider;
use TaskManager\App\Services\TaskManagerService;

class TaskManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TaskManagerService::class, static function () {
            $repository = new LaravelTaskRepository();
            return new TaskManagerService($repository);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
