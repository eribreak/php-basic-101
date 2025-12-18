<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\PostVersion;
use App\Repositories\Contracts\PostVersionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PostVersionRepository extends BaseRepository implements PostVersionRepositoryInterface
{
    public function __construct(PostVersion $model)
    {
        parent::__construct($model);
    }

    public function getByPost(Post $post): Collection
    {
        return $post->versions()->orderBy('version_number', 'desc')->get();
    }

    public function getLatestVersion(Post $post): ?PostVersion
    {
        return $post->versions()->orderBy('version_number', 'desc')->first();
    }

    public function getMaxVersionNumber(Post $post): int
    {
        return (int) $post->versions()->max('version_number') ?? 0;
    }

    public function createVersion(Post $post, int $versionNumber, ?int $createdBy = null): PostVersion
    {
        return $this->model->create([
            'post_id' => $post->id,
            'version_number' => $versionNumber,
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'content' => $post->content,
            'status' => $post->status,
            'thumbnail' => $post->thumbnail,
            'published_at' => $post->published_at,
            'created_by' => $createdBy ?? auth()->id(),
        ]);
    }
}

