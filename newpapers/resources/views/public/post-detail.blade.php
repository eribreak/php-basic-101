@extends('layouts.public')

@section('content')
<section class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:py-10">
    <div class="mt-3 grid gap-8 lg:grid-cols-[minmax(0,1fr)_300px] relative">
        <div>
            <nav class="text-[11px] text-softText mb-1">
                <ol class="flex flex-wrap items-center gap-1">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-body">Trang chủ</a>
                        <span>/</span>
                    </li>
                    @foreach($post->categories as $cat)
                        <li>
                            <a href="{{ route('categories.show', $cat->slug) }}" class="hover:text-body">
                                {{ $cat->name }}
                            </a>
                            @if($loop->last)
                            <span>/</span>
                            @else
                            <span>-</span>
                            @endif
                        </li>
                    @endforeach
                    <li class="text-body font-medium line-clamp-1">{{ $post->public_title }}</li>
                </ol>
            </nav>

            <header class="mb-6 border-b pb-4 space-y-3">
                <div class="space-y-1">
                    <h1 class="text-3xl font-semibold text-heading whitespace-normal wrap-break-word">{{ $post->public_title }}</h1>
                    <p class="mt-1 text-xs text-mutedText">
                        {{ optional($post->public_published_at)->format('d M Y') }} · {{ $post->author->name ?? 'Ẩn danh' }} ·
                        {{ $post->views_count ?? 0 }} lượt xem
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->categories as $cat)
                            <a href="{{ route('categories.show', $cat->slug) }}"
                               class="rounded-full border border-primary/20 bg-primary/10 px-2 py-0.5 text-xs text-primary hover:bg-primary/15">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                @if($post->public_thumbnail)
                    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
                        <img
                            src="{{ Str::startsWith($post->public_thumbnail, 'http') ? $post->public_thumbnail : asset('storage/'.$post->public_thumbnail) }}"
                            alt="{{ $post->public_title }}"
                            class="w-full object-cover"
                            style="max-height: 420px;"
                        >
                    </div>
                @endif
            </header>

            <article class="prose prose-sm max-w-none text-body space-y-4">
                {!! $post->public_content !!}
            </article>

                <div class="mt-12 text-sm text-softText">
                    Từ khóa:
                    @foreach($post->keywords as $keyword)
                        <a
                            href="{{ route('search', ['keyword' => $keyword->slug]) }}"
                            class="inline-block mr-2 my-0.5 rounded border border-primary/20 bg-primary/10 px-2 py-0.5 text-xs text-primary hover:bg-primary/15"
                        >{{ $keyword->name }}</a>
                    @endforeach
                </div>

            <section class="mt-10 border-t pt-6">

                <h2 class="text-sm font-semibold text-heading">Thêm bình luận</h2>

                @auth
                    <form id="comment-form" class="mt-3 space-y-3" method="POST" action="{{ route('comments.store', $post->public_slug) }}">
                        @csrf

                        <div>
                            <label class="mb-1 block text-xs font-medium text-body">Nội dung bình luận</label>
                            <textarea
                                id="comment-content"
                                name="content"
                                rows="4"
                                required
                                class="block w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                                placeholder="Nhập bình luận của bạn"
                            >{{ old('content') }}</textarea>
                            <p id="comment-error" class="mt-1 hidden text-xs text-red-600"></p>
                            @error('content')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="inline-flex items-center rounded bg-primary px-4 py-1.5 text-xs font-medium text-primary-foreground hover:bg-primary/90"
                        >
                            Gửi bình luận
                        </button>
                    </form>
                @else
                    <p class="mt-3 text-sm text-mutedText">
                        Bạn cần <a class="text-primary hover:text-primary/80" href="{{ route('login') }}">đăng nhập</a> để bình luận.
                    </p>
                @endauth
            </section>

            <section class="mt-8 border-t pt-6">
                <h2 class="mb-3 text-sm font-semibold text-heading">
                    <span id="comment-count">{{ $post->comments->count() }}</span> bình luận
                </h2>

                @if($post->comments->count())
                    <div id="comment-list" class="space-y-4">
                        @foreach($post->comments as $comment)
                            <article class="rounded border border-gray-200 border-l-2 border-l-primary/20 bg-white px-3 py-2">
                                <header class="flex items-baseline justify-between gap-2">
                                    <p class="text-sm font-semibold text-heading">
                                        {{ $comment->author_name }}
                                    </p>
                                    <p class="text-xs text-softText">
                                        {{ optional($comment->created_at)->format('d M Y H:i') }}
                                    </p>
                                </header>
                                <p class="mt-1 text-sm text-body whitespace-pre-line wrap-break-word">
                                    {{ $comment->content }}
                                </p>
                            </article>
                        @endforeach
                    </div>
                @else
                    <p id="no-comments" class="text-xs text-softText">Chưa có bình luận nào.</p>
                    <div id="comment-list" class="hidden space-y-4"></div>
                @endif
            </section>
        </div>

        <aside class="space-y-6 sticky top-20 self-start h-fit">
            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                <h3 class="text-sm font-semibold text-heading">Bài viết liên quan</h3>
                @if(isset($relatedByKeywords) && $relatedByKeywords->count())
                    <div class="mt-3 space-y-3">
                        @foreach($relatedByKeywords as $item)
                            <a href="{{ route('posts.show', $item->public_slug) }}" class="flex gap-3 group">
                                @if($item->public_thumbnail)
                                    <div class="h-16 w-16 shrink-0 overflow-hidden rounded border border-gray-100">
                                        <img src="{{ Str::startsWith($item->public_thumbnail, 'http') ? $item->public_thumbnail : asset('storage/'.$item->public_thumbnail) }}" alt="{{ $item->public_title }}" class="h-full w-full object-cover">
                                    </div>
                                @else
                                    <div class="flex h-16 w-16 shrink-0 items-center justify-center bg-gray-200 text-sm font-semibold text-mutedText">
                                        No Image
                                    </div>

                                @endif
                                <div class="min-w-0">
                                    <p class="line-clamp-2 text-sm font-medium text-heading group-hover:text-body">
                                        {{ $item->public_title }}
                                    </p>
                                    <p class="mt-1 text-xs text-softText">
                                        {{ optional($item->public_published_at)->format('d M Y') }} · {{ $item->views_count ?? 0 }} lượt xem
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="mt-3 text-xs text-softText">Chưa có bài nào.</p>
                @endif
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                <h3 class="text-sm font-semibold text-heading">Cùng danh mục</h3>
                @if(isset($relatedByCategories) && $relatedByCategories->count())
                    <div class="mt-3 space-y-3">
                        @foreach($relatedByCategories as $item)
                            <a href="{{ route('posts.show', $item->public_slug) }}" class="flex gap-3 group">
                                @if($item->public_thumbnail)
                                    <div class="h-16 w-16 shrink-0 overflow-hidden rounded border border-gray-100">
                                        <img src="{{ Str::startsWith($item->public_thumbnail, 'http') ? $item->public_thumbnail : asset('storage/'.$item->public_thumbnail) }}" alt="{{ $item->public_title }}" class="h-full w-full object-cover">
                                    </div>
                                @else
                                    <div class="flex h-16 w-16 shrink-0 items-center justify-center bg-gray-200 text-sm font-semibold text-mutedText">
                                        No Image
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="line-clamp-2 text-sm font-medium text-heading group-hover:text-body">
                                        {{ $item->public_title }}
                                    </p>
                                    <p class="mt-1 text-xs text-softText">
                                        {{ optional($item->public_published_at)->format('d M Y') }} · {{ $item->views_count ?? 0 }} lượt xem
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="mt-3 text-xs text-softText">Chưa có bài nào.</p>
                @endif
            </div>
        </aside>
    </div>
</section>

@auth
    @vite('resources/js/public/comments.js')
@endauth
@endsection

