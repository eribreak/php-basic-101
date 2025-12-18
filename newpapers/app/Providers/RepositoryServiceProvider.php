<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\CommentRepository;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Repositories\Contracts\KeywordRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\PostVersionRepositoryInterface;
use App\Repositories\KeywordRepository;
use App\Repositories\PostRepository;
use App\Repositories\PostVersionRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(KeywordRepositoryInterface::class, KeywordRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(PostVersionRepositoryInterface::class, PostVersionRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
