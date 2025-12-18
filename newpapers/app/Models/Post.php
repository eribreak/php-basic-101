<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'thumbnail',
        'excerpt',
        'content',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function keywords(): BelongsToMany
    {
        return $this->belongsToMany(Keyword::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(PostView::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(PostVersion::class)->orderBy('version_number', 'desc');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeRelated(Builder $query, Post $post, string $relation): Builder
    {
        return $query->with(['author', 'categories'])
            ->withCount('views')
            ->published()
            ->where('id', '!=', $post->id)
            ->whereHas($relation, function ($q) use ($post, $relation): void {
                $q->whereIn("{$relation}.id", $post->$relation->pluck('id'));
            })
            ->inRandomOrder()
            ->limit(3);
    }


    public function getViewsCountAttribute(): int
    {
        return $this->attributes['views_count'] ?? $this->views()->count();
    }
}
