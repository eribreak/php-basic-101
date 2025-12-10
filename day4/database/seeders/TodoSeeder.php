<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Todo;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    public function run(): void
    {
        $workCategory = Category::where('name', 'Công việc')->first();
        $personalCategory = Category::where('name', 'Cá nhân')->first();
        $studyCategory = Category::where('name', 'Học tập')->first();

        $todos = [
            [
                'title' => 'Học PHP',
                'description' => 'Nắm vững cách kết nối MySQL bằng PDO',
                'status' => 'pending',
                'category_id' => $workCategory?->id,
            ],
            [
                'title' => 'Day 3',
                'description' => 'Hoàn thành CRUD todolist',
                'status' => 'pending',
                'category_id' => $studyCategory?->id,
            ],
            [
                'title' => 'Mua sắm',
                'description' => 'Mua đồ dùng cá nhân',
                'status' => 'pending',
                'category_id' => $personalCategory?->id,
            ],
        ];

        foreach ($todos as $todo) {
            Todo::create($todo);
        }
    }
}
