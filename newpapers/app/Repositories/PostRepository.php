<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\PostVersion;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
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

    public function findPublicBySlug(string $slug): ?Post
    {
        $post = $this->model
            ->with(['author', 'categories', 'comments', 'keywords', 'latestPublishedVersion'])
            ->where('slug', $slug)
            ->first();

        if ($post && $post->public_published_at && $post->public_published_at->lte(now())) {
            return $post;
        }

        $version = PostVersion::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderByDesc('version_number')
            ->first();

        if (! $version) {
            return null;
        }

        return $this->model
            ->with(['author', 'categories', 'comments', 'keywords', 'latestPublishedVersion'])
            ->find($version->post_id);
    }

    public function getPublishedPosts(int $perPage = 9): LengthAwarePaginator
    {
        return $this->model
            ->with(['author', 'categories', 'latestPublishedVersion'])
            ->withCount('views')
            ->publiclyVisible()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function getPostsByCategorySlug(string $categorySlug, int $perPage = 9): LengthAwarePaginator
    {
        return $this->model
            ->with(['author', 'categories', 'latestPublishedVersion'])
            ->withCount('views')
            ->publiclyVisible()
            ->whereHas('categories', function ($query) use ($categorySlug): void {
                $query->where('slug', $categorySlug);
            })
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function getRelatedPosts(Post $post, string $relation, int $limit = 3): Collection
    {
        return $this->model
            ->with(['author', 'categories', 'latestPublishedVersion'])
            ->withCount('views')
            ->publiclyVisible()
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
            ->with(['author', 'categories', 'latestPublishedVersion'])
            ->withCount('views')
            ->publiclyVisible()
            ->orderByDesc('views_count')
            ->limit($limit)
            ->get();
    }

    public function searchPublishedPosts(?string $query, ?string $categorySlug, ?string $keywordSlug, int $perPage = 9): LengthAwarePaginator
    {
        $query = is_string($query) ? trim($query) : null;
        $categorySlug = is_string($categorySlug) ? trim($categorySlug) : null;
        $keywordSlug = is_string($keywordSlug) ? trim($keywordSlug) : null;

        $builder = $this->model
            ->with(['author', 'categories', 'latestPublishedVersion'])
            ->withCount('views')
            ->publiclyVisible();

        if ($query !== null && $query !== '') {
            $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $query) . '%';
            $likeSlug = '%' . str_replace(['%', '_'], ['\\%', '\\_'], Str::slug($query)) . '%';
            $builder->where(function ($q) use ($like, $likeSlug): void {
                $q->where('slug', 'like', $likeSlug)
                ->orWhere('title', 'like', $like);
                });
        }

        if ($categorySlug !== null && $categorySlug !== '') {
            $builder->whereHas('categories', function ($q) use ($categorySlug): void {
                $q->where('slug', $categorySlug);
            });
        }

        if ($keywordSlug !== null && $keywordSlug !== '') {
            $builder->whereHas('keywords', function ($q) use ($keywordSlug): void {
                $q->where('slug', $keywordSlug);
            });
        }

        return $builder
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate($perPage);
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

