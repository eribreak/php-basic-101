<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Công việc'],
            ['name' => 'Cá nhân'],
            ['name' => 'Học tập'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
