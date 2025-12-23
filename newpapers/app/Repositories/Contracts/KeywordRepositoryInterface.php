<?php

namespace App\Repositories\Contracts;

use App\Models\Keyword;
use Illuminate\Database\Eloquent\Collection;

interface KeywordRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug(string $slug): ?Keyword;

    public function findByName(string $name): ?Keyword;

    public function getAllOrderedByName(): Collection;
}

