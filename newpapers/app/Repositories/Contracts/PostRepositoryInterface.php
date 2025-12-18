<?php

namespace App\Repositories\Contracts;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug(string $slug): ?Post;

    public function getPublishedPosts(int $perPage = 10): LengthAwarePaginator;

    public function getPostsByCategorySlug(string $categorySlug, int $perPage = 10): LengthAwarePaginator;

    public function getRelatedPosts(Post $post, string $relation, int $limit = 3): Collection;

    public function getTopViewedPosts(int $limit = 3): Collection;

    public function incrementView(Post $post, string $ipAddress): void;

    public function hasViewedToday(Post $post, string $ipAddress): bool;
}

