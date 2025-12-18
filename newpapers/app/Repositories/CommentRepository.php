<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Models\Post;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }

    public function createForPost(Post $post, array $data): Comment
    {
        return $post->comments()->create($data);
    }

    public function getByPost(Post $post): Collection
    {
        return $post->comments()->get();
    }
}

