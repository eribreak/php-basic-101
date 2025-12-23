<?php

namespace App\Repositories;

use App\Models\Keyword;
use App\Repositories\Contracts\KeywordRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class KeywordRepository extends BaseRepository implements KeywordRepositoryInterface
{
    public function __construct(Keyword $model)
    {
        parent::__construct($model);
    }

    public function findBySlug(string $slug): ?Keyword
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function findByName(string $name): ?Keyword
    {
        return $this->model->where('name', $name)->first();
    }

    public function getAllOrderedByName(): Collection
    {
        return $this->model->orderBy('name')->get();
    }
}

