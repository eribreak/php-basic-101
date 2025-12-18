<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keyword_post', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keyword_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->unique(['keyword_id', 'post_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keyword_post');
    }
};

