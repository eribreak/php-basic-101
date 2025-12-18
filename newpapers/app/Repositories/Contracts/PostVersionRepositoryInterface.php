<?php

namespace App\Repositories\Contracts;

use App\Models\Post;
use App\Models\PostVersion;

interface PostVersionRepositoryInterface extends BaseRepositoryInterface
{
    public function getByPost(Post $post): \Illuminate\Database\Eloquent\Collection;

    public function getLatestVersion(Post $post): ?PostVersion;

    public function getMaxVersionNumber(Post $post): int;

    public function createVersion(Post $post, int $versionNumber, ?int $createdBy = null): PostVersion;
}

