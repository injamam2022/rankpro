<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    @include('site.include.seo', ['seo' => ['title' => $title ?? null]])
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
        <div class="rp-blog__inner">
            <header class="rp-blog__header">
                <h1>RankPro Blog</h1>
                <p>NEET strategy notes, paper analysis and RankPro product updates.</p>
            </header>

            @if($posts->count())
                <div class="rp-blog__grid">
                    @foreach($posts as $post)
                        <article class="rp-blog-card">
                            <a href="{{ route('blog.show', $post->slug) }}" class="rp-blog-card__media">
                                @if($post->image_url)
                                    <img src="{{ $post->image_url }}" alt="{{ $post->title }}">
                                @else
                                    <div class="rp-blog-card__placeholder">RankPro</div>
                                @endif
                            </a>
                            <div class="rp-blog-card__body">
                                <time datetime="{{ optional($post->published_at)->toDateString() }}">
                                    {{ optional($post->published_at)->format('d M Y') }}
                                </time>
                                <h2>
                                    <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                </h2>
                                @if($post->excerpt)
                                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($post->excerpt), 140) }}</p>
                                @endif
                                <a class="rp-blog-card__link" href="{{ route('blog.show', $post->slug) }}">Read more</a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="rp-blog__pager">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="rp-blog__empty">
                    <p>No blog posts published yet. Check back soon for NEET strategy notes and RankPro updates.</p>
                </div>
            @endif
        </div>
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
