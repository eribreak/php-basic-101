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

        $post = $this->postRepository->findPublicBySlug($slug);

        if (!$post || !$post->public_published_at) {
            abort(404);
        }

        $comment = $this->commentRepository->createForPost($post, [
            'author_name' => $request->user()->name,
            'author_email' => $request->user()->email,
            'content' => $request->content,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Bình luận của bạn đã được gửi!',
                'comment' => [
                    'author_name' => $comment->author_name,
                    'content' => $comment->content,
                    'created_at' => optional($comment->created_at)->format('d M Y H:i'),
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Bình luận của bạn đã được gửi!');
    }
}
