@extends('layouts.public')

@section('content')
<section class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:py-10">
    <div class="mt-3 grid gap-8 lg:grid-cols-[minmax(0,1fr)_300px]">
        <div>
            <nav class="text-[11px] text-softText">
                <ol class="flex flex-wrap items-center gap-1">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-body">Trang chủ</a>
                        <span>/</span>
                    </li>
                    @if($post->categories->first())
                        <li>
                            <a href="{{ route('categories.show', $post->categories->first()->slug) }}" class="hover:text-body">
                                {{ $post->categories->first()->name }}
                            </a>
                            <span>/</span>
                        </li>
                    @endif
                    <li class="text-body font-medium line-clamp-1">{{ $post->title }}</li>
                </ol>
            </nav>

            <header class="mb-6 border-b pb-4 space-y-3">
                <div class="space-y-1">
                    <h1 class="text-3xl font-semibold text-heading">{{ $post->title }}</h1>
                    <p class="mt-1 text-xs text-mutedText">
                        {{ optional($post->published_at)->format('d M Y') }} · {{ $post->author->name ?? 'Ẩn danh' }} ·
                        {{ $post->views_count ?? 0 }} lượt xem
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->categories as $cat)
                            <a href="{{ route('categories.show', $cat->slug) }}"
                               class="rounded-full bg-chipBg px-2 py-0.5 text-xs text-body hover:bg-gray-200">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                @if($post->thumbnail)
                    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
                        <img
                            src="{{ Str::startsWith($post->thumbnail, 'http') ? $post->thumbnail : asset('storage/'.$post->thumbnail) }}"
                            alt="{{ $post->title }}"
                            class="w-full object-cover"
                            style="max-height: 420px;"
                        >
                    </div>
                @endif
            </header>

            <article class="prose prose-sm max-w-none text-body">
                {!! $post->content !!}
            </article>

            <section class="mt-10 border-t pt-6">
                <h2 class="text-sm font-semibold text-heading">Thêm bình luận</h2>

                @auth
                    <form class="mt-3 space-y-3" method="POST" action="{{ route('comments.store', $post->slug) }}">
                        @csrf

                        <div>
                            <label class="mb-1 block text-xs font-medium text-body">Nội dung bình luận</label>
                            <textarea
                                name="content"
                                rows="4"
                                required
                                class="block w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                                placeholder="Nhập bình luận của bạn"
                            >{{ old('content') }}</textarea>
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
                        Bạn cần <a class="text-blue-600 hover:text-blue-800" href="{{ route('login') }}">đăng nhập</a> để bình luận.
                    </p>
                @endauth
            </section>

            <section class="mt-8 border-t pt-6">
                <h2 class="mb-3 text-sm font-semibold text-heading">
                    {{ $post->comments->count() }} bình luận
                </h2>

                @if($post->comments->count())
                    <div class="space-y-4">
                        @foreach($post->comments as $comment)
                            <article class="rounded border border-gray-200 bg-white px-3 py-2">
                                <header class="flex items-baseline justify-between gap-2">
                                    <p class="text-sm font-semibold text-heading">
                                        {{ $comment->author_name }}
                                    </p>
                                    <p class="text-xs text-softText">
                                        {{ optional($comment->created_at)->format('d M Y H:i') }}
                                    </p>
                                </header>
                                <p class="mt-1 text-sm text-body">
                                    {{ $comment->content }}
                                </p>
                            </article>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-softText">Chưa có bình luận nào.</p>
                @endif
            </section>
        </div>

        <aside class="space-y-6">
            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                <h3 class="text-sm font-semibold text-heading">Bài viết liên quan</h3>
                @if(isset($relatedByKeywords) && $relatedByKeywords->count())
                    <div class="mt-3 space-y-3">
                        @foreach($relatedByKeywords as $item)
                            <a href="{{ route('posts.show', $item->slug) }}" class="flex gap-3 group">
                                @if($item->thumbnail)
                                    <div class="h-16 w-16 shrink-0 overflow-hidden rounded border border-gray-100">
                                        <img src="{{ Str::startsWith($item->thumbnail, 'http') ? $item->thumbnail : asset('storage/'.$item->thumbnail) }}" alt="{{ $item->title }}" class="h-full w-full object-cover">
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="line-clamp-2 text-sm font-medium text-heading group-hover:text-body">
                                        {{ $item->title }}
                                    </p>
                                    <p class="mt-1 text-xs text-softText">
                                        {{ optional($item->published_at)->format('d M Y') }} · {{ $item->views_count ?? 0 }} lượt xem
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
                            <a href="{{ route('posts.show', $item->slug) }}" class="flex gap-3 group">
                                @if($item->thumbnail)
                                    <div class="h-16 w-16 shrink-0 overflow-hidden rounded border border-gray-100">
                                        <img src="{{ Str::startsWith($item->thumbnail, 'http') ? $item->thumbnail : asset('storage/'.$item->thumbnail) }}" alt="{{ $item->title }}" class="h-full w-full object-cover">
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="line-clamp-2 text-sm font-medium text-heading group-hover:text-body">
                                        {{ $item->title }}
                                    </p>
                                    <p class="mt-1 text-xs text-softText">
                                        {{ optional($item->published_at)->format('d M Y') }} · {{ $item->views_count ?? 0 }} lượt xem
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
@endsection

