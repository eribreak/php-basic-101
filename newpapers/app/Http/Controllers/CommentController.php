<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
        private CommentRepositoryInterface $commentRepository
    ) {
    }

    public function store(Request $request, string $slug)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $post = $this->postRepository->findBySlug($slug);

        if (!$post || $post->status !== 'published') {
            abort(404);
        }

        $this->commentRepository->createForPost($post, [
            'author_name' => $request->user()->name,
            'author_email' => $request->user()->email,
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Bình luận của bạn đã được gửi!');
    }
}
