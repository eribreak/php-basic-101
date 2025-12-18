<?php

namespace App\Repositories;

use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    public function __construct(Post $model)
    {
        parent::__construct($model);
    }

    public function findBySlug(string $slug): ?Post
    {
        return $this->model
            ->with(['author', 'categories', 'comments', 'keywords'])
            ->where('slug', $slug)
            ->first();
    }

    public function getPublishedPosts(int $perPage = 9): LengthAwarePaginator
    {
        return $this->model
            ->with(['author', 'categories'])
            ->withCount('views')
            ->published()
            ->latest('published_at')
            ->paginate($perPage);
    }

    public function getPostsByCategorySlug(string $categorySlug, int $perPage = 9): LengthAwarePaginator
    {
        return $this->model
            ->with(['author', 'categories'])
            ->withCount('views')
            ->published()
            ->whereHas('categories', function ($query) use ($categorySlug): void {
                $query->where('slug', $categorySlug);
            })
            ->latest('published_at')
            ->paginate($perPage);
    }

    public function getRelatedPosts(Post $post, string $relation, int $limit = 3): Collection
    {
        return $this->model
            ->with(['author', 'categories'])
            ->withCount('views')
            ->published()
            ->where('id', '!=', $post->id)
            ->whereHas($relation, function ($q) use ($post, $relation): void {
                $q->whereIn("{$relation}.id", $post->$relation->pluck('id'));
            })
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    public function getTopViewedPosts(int $limit = 3): Collection
    {
        return $this->model
            ->with(['author', 'categories'])
            ->withCount('views')
            ->published()
            ->orderByDesc('views_count')
            ->limit($limit)
            ->get();
    }

    public function incrementView(Post $post, string $ipAddress): void
    {
        $post->views()->create([
            'ip_address' => $ipAddress,
        ]);
    }

    public function hasViewedToday(Post $post, string $ipAddress): bool
    {
        return $post->views()
            ->where('ip_address', $ipAddress)
            ->where('created_at', '>=', now()->subDay())
            ->exists();
    }
}

