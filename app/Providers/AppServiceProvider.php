<?php

namespace App\Providers;

use App\Repositories\CommentsRepository;
use App\Repositories\LessonRepository;
use App\Services\CommentsService;
use App\Services\LessonsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CommentsService::class, function ($app) {
            return new CommentsService($app->make(CommentsRepository::class));
        });

        $this->app->singleton(LessonsService::class, function ($app) {
            return new LessonsService($app->make(LessonRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
