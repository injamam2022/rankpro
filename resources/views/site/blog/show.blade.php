<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    {{-- Meta title / meta description / keywords are SEO-only (head tags), not page content --}}
    @include('site.include.seo', ['seo' => [
        'name' => $post->title,
        'title' => $post->meta_title ?: $post->title,
        'description' => $post->meta_description ?: ($post->excerpt ?: null),
        'keywords' => $post->keywords ?: null,
        'image' => $post->image_url ?: null,
    ]])
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="shortcut icon" href="{{ asset('web/images/favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('web/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('web/bootstrap-5.0.2/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/redesign.css') }}" rel="stylesheet">
    @include('site.include.head_meta')
</head>
<body>
    @include('site.include.body_meta')
    @include('site.include.header')

    <section class="rp-page rp-blog">
        <article class="rp-blog-post">
            <a class="rp-blog-post__back" href="{{ route('blog') }}">&larr; Back to Blog</a>
            <header class="rp-blog-post__header">
                <time datetime="{{ optional($post->published_at)->toDateString() }}">
                    {{ optional($post->published_at)->format('d M Y') }}
                </time>
                <h1>{{ $post->title }}</h1>
                @if($post->excerpt)
                    <p class="rp-blog-post__excerpt">{{ $post->excerpt }}</p>
                @endif
            </header>

            @if($post->image_url)
                <div class="rp-blog-post__hero">
                    <img src="{{ $post->image_url }}" alt="{{ $post->title }}">
                </div>
            @endif

            <div class="rp-blog-post__content">
                {!! $post->content !!}
            </div>
        </article>
    </section>

    @include('site.include.footer')
    @include('site.include.call_to_action')
    @include('site.include.back_to_top')

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('web/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('web/js/main.js') }}"></script>
</body>
</html>
