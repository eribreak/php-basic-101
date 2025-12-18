<?php

namespace App\Observers;

use App\Models\Post;
use App\Repositories\Contracts\PostVersionRepositoryInterface;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        $this->createVersion($post, 1);
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        $dirtyFields = ['title', 'slug', 'excerpt', 'content', 'status', 'thumbnail', 'published_at'];
        $hasContentChange = false;

        foreach ($dirtyFields as $field) {
            if ($post->wasChanged($field)) {
                $hasContentChange = true;
                break;
            }
        }

        if ($hasContentChange) {
            $versionRepository = app(PostVersionRepositoryInterface::class);
            $latestVersion = $versionRepository->getMaxVersionNumber($post);
            $this->createVersion($post, $latestVersion + 1);
        }
    }

    protected function createVersion(Post $post, int $versionNumber): void
    {
        $versionRepository = app(PostVersionRepositoryInterface::class);
        $versionRepository->createVersion($post, $versionNumber);
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }
}
