<?php

declare(strict_types=1);

namespace App\Models;

use App\Database;
use PDO;
use PDOException;

class TodoModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(?string $status = null): array
    {
        $sql = "SELECT 
                    t.id,
                    t.title,
                    t.description,
                    t.status,
                    t.created_at,
                    t.updated_at,
                    c.name as category_name
                FROM todos t
                LEFT JOIN categories c ON t.category_id = c.id";

        if ($status !== null) {
            $sql .= " WHERE t.status = :status";
        }

        $sql .= " ORDER BY t.created_at DESC";

        try {
            $stmt = $this->db->prepare($sql);
            if ($status !== null) {
                $stmt->bindValue(':status', $status, PDO::PARAM_STR);
            }
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new PDOException("Lỗi: " . $e->getMessage());
        }
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT 
                    t.id,
                    t.title,
                    t.description,
                    t.status,
                    t.category_id,
                    t.created_at,
                    t.updated_at,
                    c.name as category_name
                FROM todos t
                LEFT JOIN categories c ON t.category_id = c.id
                WHERE t.id = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            throw new PDOException("Lỗi: " . $e->getMessage());
        }
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO todos (title, description, status, category_id) 
                VALUES (:title, :description, :status, :category_id)";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':title', $data['title'], PDO::PARAM_STR);
            $stmt->bindValue(':description', $data['description'] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':status', $data['status'] ?? 'pending', PDO::PARAM_STR);
            $stmt->bindValue(':category_id', $data['category_id'] ?? null, PDO::PARAM_INT);
            $stmt->execute();

            return (int) $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new PDOException("Lỗi: " . $e->getMessage());
        }
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $allowedFields = ['title', 'description', 'status', 'category_id'];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $fields[] = "{$field} = :{$field}";
            }
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE todos SET " . implode(', ', $fields) . " WHERE id = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $type = ($field === 'category_id') ? PDO::PARAM_INT : PDO::PARAM_STR;
                    $stmt->bindValue(":{$field}", $data[$field], $type);
                }
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException("Lỗi: " . $e->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM todos WHERE id = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException("Lỗi: " . $e->getMessage());
        }
    }

    public function getAllCategories(): array
    {
        $sql = "SELECT id, name FROM categories ORDER BY name";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new PDOException("Lỗi: " . $e->getMessage());
        }
    }
}

