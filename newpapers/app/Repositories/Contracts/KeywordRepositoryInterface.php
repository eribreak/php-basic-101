<?php

namespace App\Repositories\Contracts;

use App\Models\Keyword;

interface KeywordRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug(string $slug): ?Keyword;

    public function findByName(string $name): ?Keyword;
}

