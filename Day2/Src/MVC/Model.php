<?php

declare(strict_types=1);

namespace Src\MVC;

/**
 * Base Model - Tất cả models đều kế thừa từ class này
 * Model xử lý data và business logic
 */
abstract class Model
{
    /**
     * Lấy tất cả records
     */
    abstract public function all(): array;

    /**
     * Tìm record theo ID
     */
    abstract public function find(int $id): ?array;

    /**
     * Tạo record mới
     */
    abstract public function create(array $data): bool;

    /**
     * Cập nhật record
     */
    abstract public function update(int $id, array $data): bool;

    /**
     * Xóa record
     */
    abstract public function delete(int $id): bool;
}

