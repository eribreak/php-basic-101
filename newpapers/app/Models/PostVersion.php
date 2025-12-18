<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostVersion extends Model
{
    protected $fillable = [
        'post_id',
        'version_number',
        'title',
        'slug',
        'excerpt',
        'content',
        'status',
        'thumbnail',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
