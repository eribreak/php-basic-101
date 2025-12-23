@extends('layouts.public')

@section('content')
<section class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:py-10">
    <header class="mb-6 border-b pb-4">
        <div class="flex flex-col gap-2">
            <div>
                <h1 class="text-2xl font-semibold text-heading">Tin mới nhất</h1>
                <p class="mt-1 text-sm text-mutedText">Các bài viết mới được xuất bản gần đây.</p>
            </div>

            @if($categories->count())
                <div class="mt-2 flex flex-wrap items-center gap-2">
                    @foreach($categories as $category)
                        <a href="{{ route('categories.show', $category->slug) }}"
                           class="rounded-full border border-primary/20 bg-primary/10 px-2 py-0.5 text-xs text-primary hover:bg-primary/15">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </header>

    @php
        $featured = $posts->first();
        $others = $posts->slice(1);
    @endphp

    @if($featured)
        <section class="mb-8 grid gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(260px,1fr)]">
            <article class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <a href="{{ route('posts.show', $featured->public_slug) }}" class="block">
                    <div class="w-full overflow-hidden bg-chipBg">
                            @if($featured->public_thumbnail)
                            <img
                                    src="{{ Str::startsWith($featured->public_thumbnail, 'http') ? $featured->public_thumbnail : asset('storage/'.$featured->public_thumbnail) }}"
                                    alt="{{ $featured->public_title }}"
                                class="h-full w-full object-contain transition-transform duration-300 hover:scale-[1.02]"
                            >
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-gray-200 text-sm font-semibold text-mutedText">
                                No Image
                            </div>
                        @endif
                    </div>
                </a>

                <div class="p-4 sm:p-5">
                    <div class="mb-2 flex flex-wrap items-center gap-2 text-[11px] text-softText">
                        @foreach($featured->categories as $cat)
                            <a href="{{ route('categories.show', $cat->slug) }}"
                               class="rounded-full border border-primary/20 bg-primary/10 px-2 py-0.5 text-xs text-primary hover:bg-primary/15">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                        <span class="ml-auto">
                                {{ optional($featured->public_published_at)->format('d M Y') }} · {{ $featured->views_count ?? 0 }} lượt xem
                        </span>
                    </div>

                    <h2 title="{{ $featured->public_title }}" class="text-xl font-semibold leading-snug text-heading overflow-hidden text-ellipsis whitespace-nowrap">
                            <a href="{{ route('posts.show', $featured->public_slug) }}" class="hover:text-body">
                                {{ $featured->public_title }}
                        </a>
                    </h2>

                    <p class="mt-2 text-sm text-body line-clamp-3">
                            {{ \Illuminate\Support\Str::limit($featured->public_excerpt ?? strip_tags($featured->public_content), 220) }}
                    </p>

                    <div class="mt-4 flex items-center justify-between text-xs text-softText">
                        <p>Tác giả: <span class="font-medium text-body">{{ $featured->author->name ?? 'Ẩn danh' }}</span></p>
                            <a href="{{ route('posts.show', $featured->public_slug) }}" class="font-medium text-body hover:text-heading">
                            Đọc chi tiết →
                        </a>
                    </div>
                </div>
            </article>

            <aside class="space-y-4">
                <div class="rounded-lg border border-gray-200 bg-white p-4 text-sm shadow-sm">
                    <h2 class="text-xs font-semibold uppercase tracking-[0.12em] text-softText">Tin đáng chú ý</h2>
                    <div class="mt-3 space-y-3">
                        @foreach($hotPosts as $hot)
                                <a href="{{ route('posts.show', $hot->public_slug) }}" class="flex gap-3 group">
                                    @if($hot->public_thumbnail)
                                    <div class="h-14 w-20 shrink-0 overflow-hidden rounded bg-gray-100">
                                            <img src="{{ Str::startsWith($hot->public_thumbnail, 'http') ? $hot->public_thumbnail : asset('storage/'.$hot->public_thumbnail) }}" alt="{{ $hot->public_title }}" class="h-full w-full object-cover">
                                    </div>
                                @else
                                    <div class="flex h-16 w-16 shrink-0 items-center justify-center bg-gray-200 text-xs font-semibold text-mutedText">
                                        No Image
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p title="{{ $hot->public_title }}" class="line-clamp-2 text-xs font-medium text-heading group-hover:text-body">
                                            {{ $hot->public_title }}
                                    </p>
                                    <p class="mt-1 text-[11px] text-softText">
                                            {{ optional($hot->public_published_at)->format('d M Y') }} · {{ $hot->views_count ?? 0 }} lượt xem
                                    </p>
                                </div>
                            </a>
                        @endforeach

                        @if($others->isEmpty())
                            <p class="text-xs text-softText">Chưa có thêm bài viết nào.</p>
                        @endif
                    </div>
                </div>

                <div class="hidden rounded-lg border border-gray-200 bg-white p-4 text-xs text-mutedText shadow-sm sm:block">
                    <h2 class="mb-1 text-xs font-semibold uppercase tracking-[0.12em] text-softText">NewPaper</h2>
                    <p class="text-sm text-body">Nơi cung cấp tin tức mới nhất, được cập nhật liên tục hoặc là không liên tục lắm.</p>
                </div>
            </aside>
        </section>
    @endif

    <div class="mt-8">
        <div>
            @if($others->count())
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach($others as $post)
                            <article class="flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                        <a href="{{ route('posts.show', $post->public_slug) }}" class="block w-full overflow-hidden bg-chipBg">
                                    @if($post->public_thumbnail)
                                    <img
                                            src="{{ Str::startsWith($post->public_thumbnail, 'http') ? $post->public_thumbnail : asset('storage/'.$post->public_thumbnail) }}"
                                            alt="{{ $post->public_title }}"
                                        class="h-full w-full object-contain transition-transform duration-300 hover:scale-[1.03]"
                                    >
                                @else
                                    <div class="flex h-80 w-full items-center justify-center bg-gray-200 text-xs font-semibold text-mutedText">
                                        No Image
                                    </div>
                                @endif
                            </a>

                            <div class="flex flex-1 flex-col p-3">
                                    <h2 title="{{ $post->public_title }}" class="text-sm font-semibold text-heading line-clamp-2 overflow-hidden text-ellipsis whitespace-nowrap">
                                        <a href="{{ route('posts.show', $post->public_slug) }}" class="hover:text-body">
                                            {{ $post->public_title }}
                                    </a>
                                </h2>

                                <p class="mt-1 text-[11px] text-softText">
                                        {{ optional($post->public_published_at)->format('d M Y') }} · {{ $post->views_count ?? 0 }} lượt xem
                                </p>

                                <p class="mt-2 text-xs text-body line-clamp-3">
                                        {{ \Illuminate\Support\Str::limit($post->public_excerpt ?? strip_tags($post->public_content), 140) }}
                                </p>

                                <div class="mt-3 flex items-center justify-between gap-2">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($post->categories as $cat)
                                            <a href="{{ route('categories.show', $cat->slug) }}"
                                               class="rounded-full border border-primary/20 bg-primary/10 px-2 py-0.5 text-xs text-primary hover:bg-primary/15">
                                                {{ $cat->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                        <a href="{{ route('posts.show', $post->public_slug) }}" class="text-[11px] font-medium text-body hover:text-heading">
                                        Đọc →
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $posts->links() }}
                </div>
            @else
                <p class="text-sm text-softText">Chưa có bài viết nào.</p>
            @endif
        </div>
    </div>
</section>
@endsection

