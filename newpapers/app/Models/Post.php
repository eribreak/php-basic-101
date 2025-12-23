<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'publish_reminder_sent_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'publish_reminder_sent_at' => 'datetime',
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

    public function latestPublishedVersion(): HasOne
    {
        return $this->hasOne(PostVersion::class)->ofMany(
            ['version_number' => 'max'],
            function ($query): void {
                $query->where('status', 'published');
            }
        );
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

    public function scopePubliclyVisible(Builder $query): Builder
    {
        return $query->whereHas('latestPublishedVersion', function ($q): void {
            $q->whereNotNull('published_at')
                ->where('published_at', '<=', now());
        });
    }

    public function getPublicTitleAttribute(): string
    {
        if ($this->status === 'published') {
            return (string) ($this->attributes['title'] ?? '');
        }

        $version = $this->relationLoaded('latestPublishedVersion')
            ? $this->latestPublishedVersion
            : $this->latestPublishedVersion()->first();

        return (string) ($version?->title ?? '');
    }

    public function getPublicSlugAttribute(): string
    {
        if ($this->status === 'published') {
            return (string) ($this->attributes['slug'] ?? '');
        }

        $version = $this->relationLoaded('latestPublishedVersion')
            ? $this->latestPublishedVersion
            : $this->latestPublishedVersion()->first();

        return (string) ($version?->slug ?? '');
    }

    public function getPublicExcerptAttribute(): ?string
    {
        if ($this->status === 'published') {
            return $this->attributes['excerpt'] ?? null;
        }

        $version = $this->relationLoaded('latestPublishedVersion')
            ? $this->latestPublishedVersion
            : $this->latestPublishedVersion()->first();

        return $version?->excerpt;
    }

    public function getPublicContentAttribute(): string
    {
        if ($this->status === 'published') {
            return (string) ($this->attributes['content'] ?? '');
        }

        $version = $this->relationLoaded('latestPublishedVersion')
            ? $this->latestPublishedVersion
            : $this->latestPublishedVersion()->first();

        return (string) ($version?->content ?? '');
    }

    public function getPublicThumbnailAttribute(): ?string
    {
        if ($this->status === 'published') {
            return $this->attributes['thumbnail'] ?? null;
        }

        $version = $this->relationLoaded('latestPublishedVersion')
            ? $this->latestPublishedVersion
            : $this->latestPublishedVersion()->first();

        return $version?->thumbnail;
    }

    public function getPublicPublishedAtAttribute(): ?\Illuminate\Support\Carbon
    {
        if ($this->status === 'published') {
            return $this->published_at;
        }

        $version = $this->relationLoaded('latestPublishedVersion')
            ? $this->latestPublishedVersion
            : $this->latestPublishedVersion()->first();

        return $version?->published_at;
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
