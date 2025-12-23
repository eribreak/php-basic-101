@extends('layouts.public')

@section('content')
<section class="mx-auto max-w-5xl px-4 py-8 sm:px-6">
    <header class="mb-6 border-b pb-4">
        <div class="flex flex-col gap-2">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.15em] text-softText">Tìm kiếm</p>
                    <h1 class="mt-1 text-2xl font-semibold text-heading">Kết quả bài viết</h1>
                    <p class="mt-1 text-sm text-mutedText">Tìm theo tiêu đề/nội dung, lọc theo danh mục và keyword.</p>
                </div>
                <a href="{{ route('home') }}" class="text-xs text-softText hover:text-body">← Về trang chủ</a>
            </div>

            <form method="GET" action="{{ route('search') }}" class="mt-3 grid gap-3 rounded-lg border border-gray-200 bg-white p-4 shadow-sm sm:grid-cols-[1fr_180px_180px_auto] sm:items-end">
                <div>
                    <label class="mb-1 block text-xs font-medium text-body">Từ khoá</label>
                    <input
                        type="text"
                        name="q"
                        value="{{ $q ?? '' }}"
                        placeholder="Nhập từ khoá..."
                        class="block w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                    />
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-body">Danh mục</label>
                    <select
                        name="category"
                        class="block w-full rounded border border-gray-300 bg-white px-2 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                    >
                        <option value="">Tất cả</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" @selected(($selectedCategory ?? '') === $category->slug)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-body">Keyword</label>
                    <select
                        name="keyword"
                        class="block w-full rounded border border-gray-300 bg-white px-2 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                    >
                        <option value="">Tất cả</option>
                        @foreach($keywords as $kw)
                            <option value="{{ $kw->slug }}" @selected(($selectedKeyword ?? '') === $kw->slug)>
                                {{ $kw->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded bg-primary px-4 py-2 text-xs font-medium text-primary-foreground hover:bg-primary/90"
                    >
                        Tìm
                    </button>
                    <a
                        href="{{ route('search') }}"
                        class="inline-flex items-center justify-center rounded border border-gray-300 px-4 py-2 text-xs font-medium text-body hover:bg-waterWhite"
                    >
                        Xoá lọc
                    </a>
                </div>
            </form>
        </div>
    </header>

    <div class="flex items-center justify-between text-xs text-softText">
        <p>
            Tìm thấy <span class="font-medium text-body">{{ $posts->total() }}</span> bài viết
            @if(($q ?? '') !== '')
                cho "<span class="font-medium text-body">{{ $q }}</span>"
            @endif
        </p>
    </div>

    <div class="mt-4">
        @if($posts->count())
            <div class="space-y-4">
                @foreach($posts as $post)
                    <article class="rounded-lg border border-gray-200 bg-white px-4 py-3 shadow-sm">
                        <div class="flex gap-3">
                            <a href="{{ route('posts.show', $post->public_slug) }}" class="block h-24 w-32 shrink-0 overflow-hidden rounded-md bg-chipBg">
                                @if($post->public_thumbnail)
                                    <img
                                        src="{{ Str::startsWith($post->public_thumbnail, 'http') ? $post->public_thumbnail : asset('storage/'.$post->public_thumbnail) }}"
                                        alt="{{ $post->public_title }}"
                                        class="h-full w-full object-cover"
                                    >
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gray-200 text-xs font-semibold text-mutedText">
                                        No image
                                    </div>
                                @endif
                            </a>

                            <div class="flex-1 min-w-0">
                                <header class="flex flex-col gap-1 sm:flex-row sm:items-baseline sm:justify-between">
                                    <h2 class="text-base font-semibold text-heading line-clamp-2">
                                        <a href="{{ route('posts.show', $post->public_slug) }}" class="hover:text-body">
                                            {{ $post->public_title }}
                                        </a>
                                    </h2>
                                    <p class="text-[11px] text-softText">
                                        {{ optional($post->public_published_at)->format('d M Y') }} · {{ $post->author->name ?? 'Ẩn danh' }} ·
                                        {{ $post->views_count ?? 0 }} lượt xem
                                    </p>
                                </header>

                                <p class="mt-2 text-sm text-body line-clamp-3">
                                    {{ \Illuminate\Support\Str::limit($post->public_excerpt ?? strip_tags($post->public_content), 220) }}
                                </p>

                                @if($post->categories->count())
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        @foreach($post->categories as $cat)
                                            <a href="{{ route('categories.show', $cat->slug) }}"
                                               class="rounded-full border border-gray-200 px-2 py-0.5 text-[10px] text-body hover:bg-waterWhite">
                                                {{ $cat->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        @else
            <p class="text-sm text-softText">Không có kết quả phù hợp.</p>
        @endif
    </div>
</section>
@endsection
