<?php

namespace App\Repositories\Contracts;

use App\Models\Category;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug(string $slug): ?Category;

    public function getAllOrderedByName(): \Illuminate\Database\Eloquent\Collection;
}

