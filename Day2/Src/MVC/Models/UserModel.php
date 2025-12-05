<?php

declare(strict_types=1);

namespace Src\MVC\Models;

use Src\MVC\Model;

class UserModel extends Model
{
    private array $users = [
        [
            'id' => 1,
            'name' => 'Nguyễn Văn A',
            'email' => 'a@example.com',
            'age' => 25,
            'created_at' => '2025-01-15'
        ],
        [
            'id' => 2,
            'name' => 'Trần Thị B',
            'email' => 'b@example.com',
            'age' => 30,
            'created_at' => '2025-01-20'
        ],
        [
            'id' => 3,
            'name' => 'Lê Văn C',
            'email' => 'c@example.com',
            'age' => 28,
            'created_at' => '2025-02-01'
        ],
    ];

    public function all(): array
    {
        return $this->users;
    }

    public function find(int $id): ?array
    {
        foreach ($this->users as $user) {
            if ($user['id'] === $id) {
                return $user;
            }
        }
        return null; 
    }

    public function create(array $data): bool
    {
        $newId = 1;
        foreach ($this->users as $user) {
            if ($user['id'] >= $newId) {
                $newId = $user['id'] + 1;
            }
        }

        $newUser = [
            'id' => $newId,
            'name' => $data['name'] ?? '',
            'email' => $data['email'] ?? '',
            'age' => $data['age'] ?? null,
            'created_at' => date('Y-m-d')
        ];

        $this->users[] = $newUser;
        return true;
    }

    public function update(int $id, array $data): bool
    {
        foreach ($this->users as $index => $user) {
            if ($user['id'] === $id) {
                $this->users[$index]['name'] = $data['name'] ?? $user['name'];
                $this->users[$index]['email'] = $data['email'] ?? $user['email'];
                $this->users[$index]['age'] = $data['age'] ?? $user['age'];
                return true;
            }
        }
        return false; 
    }

    public function delete(int $id): bool
    {
        foreach ($this->users as $index => $user) {
            if ($user['id'] === $id) {
                unset($this->users[$index]);
                $this->users = array_values($this->users); 
                return true;
            }
        }
        return false; 
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        foreach ($this->users as $user) {
            if ($user['email'] === $email) {
                if ($excludeId !== null && $user['id'] === $excludeId) {
                    continue;
                }
                return true; 
            }
        }
        return false; 
    }
}

