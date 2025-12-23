<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\KeywordRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicPostController extends Controller
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private KeywordRepositoryInterface $keywordRepository
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
        $post = $this->postRepository->findPublicBySlug($slug);

        if (! $post) {
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

    public function search(Request $request): View
    {
        $q = $request->query('q');
        $categorySlug = $request->query('category');
        $keywordSlug = $request->query('keyword');

        $posts = $this->postRepository->searchPublishedPosts(
            is_string($q) ? $q : null,
            is_string($categorySlug) ? $categorySlug : null,
            is_string($keywordSlug) ? $keywordSlug : null,
            9
        );

        $posts->withQueryString();

        $categories = $this->categoryRepository->getAllOrderedByName();
        $keywords = $this->keywordRepository->getAllOrderedByName();

        return view('public.search', [
            'posts' => $posts,
            'categories' => $categories,
            'keywords' => $keywords,
            'q' => is_string($q) ? $q : '',
            'selectedCategory' => is_string($categorySlug) ? $categorySlug : '',
            'selectedKeyword' => is_string($keywordSlug) ? $keywordSlug : '',
        ]);
    }
}
