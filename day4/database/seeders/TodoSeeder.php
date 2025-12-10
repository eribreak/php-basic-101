<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Todo;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    public function run(): void
    {
        Todo::factory()->count(3)->create();
    }
}
