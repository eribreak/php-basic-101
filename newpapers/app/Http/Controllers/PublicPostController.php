<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicPostController extends Controller
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
        private CategoryRepositoryInterface $categoryRepository
    ) {
    }

    public function index(Request $request): View
    {
        $posts = $this->postRepository->getPublishedPosts(9);
        $categories = $this->categoryRepository->getAllOrderedByName();
        $hotPosts = $this->postRepository->getTopViewedPosts(3);

        return view('public.home', [
            'posts' => $posts,
            'categories' => $categories,
            'hotPosts' => $hotPosts,
        ]);
    }

    public function show(string $slug, Request $request): View
    {
        $post = $this->postRepository->findBySlug($slug);

        if (!$post || $post->status !== 'published') {
            abort(404);
        }

        $post->loadCount('views');

        $ipAddress = $request->ip();

        if (!$this->postRepository->hasViewedToday($post, $ipAddress)) {
            $this->postRepository->incrementView($post, $ipAddress);
            $post->refresh();
            $post->loadCount('views');
        }

        $relatedByKeywords = $this->postRepository->getRelatedPosts($post, 'keywords', 3);
        $relatedByCategories = $this->postRepository->getRelatedPosts($post, 'categories', 3);

        return view('public.post-detail', [
            'post' => $post,
            'relatedByKeywords' => $relatedByKeywords,
            'relatedByCategories' => $relatedByCategories,
        ]);
    }

    public function category(string $slug, Request $request): View
    {
        $category = $this->categoryRepository->findBySlug($slug);

        if (!$category) {
            abort(404);
        }

        $posts = $this->postRepository->getPostsByCategorySlug($slug, 9);

        return view('public.category', [
            'category' => $category,
            'posts' => $posts,
        ]);
    }
}
