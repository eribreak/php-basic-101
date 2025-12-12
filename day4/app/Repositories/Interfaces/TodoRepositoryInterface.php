<?php

namespace App\Repositories\Interfaces;

use App\Models\Todo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TodoRepositoryInterface
{
    public function getAllForUser(int $userId, ?string $status = null, ?string $priority = null, ?string $search = null): Collection;

    public function paginateForUser(int $userId, ?string $status = null, ?string $priority = null, ?string $search = null, int $perPage = 10): LengthAwarePaginator;

    public function findByIdAndUser(int $id, int $userId): ?Todo;

    public function create(array $data): Todo;

    public function update(Todo $todo, array $data): bool;

    public function delete(Todo $todo): bool;
}

