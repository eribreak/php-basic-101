<?php

namespace App\Repositories\Contracts;

use App\Models\Comment;
use App\Models\Post;

interface CommentRepositoryInterface extends BaseRepositoryInterface
{
    public function createForPost(Post $post, array $data): Comment;

    public function getByPost(Post $post): \Illuminate\Database\Eloquent\Collection;
}

