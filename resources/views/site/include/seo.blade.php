@php
    $jsonFlags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG;

    if (class_exists(\App\Support\Seo::class)) {
        $meta = \App\Support\Seo::resolve($seo ?? []);
        $siteName = \App\Support\Seo::SITE_NAME;
        $locale = \App\Support\Seo::LOCALE;
    } else {
        $override = is_array($seo ?? null) ? $seo : [];
        $title = trim((string) ($override['title'] ?? $title ?? 'RankPro'));
        $description = trim((string) ($override['description'] ?? $meta_description ?? 'NEET mock tests and test series by RankPro.'));
        $meta = [
            'title' => $title !== '' ? $title : 'RankPro',
            'description' => $description,
            'keywords' => 'NEET mock test, NEET test series, RankPro',
            'robots' => 'index, follow',
            'canonical' => url()->current(),
            'image' => asset('web/images/logo.png'),
            'og_type' => 'website',
            'schema' => ['@context' => 'https://schema.org', '@type' => 'WebSite', 'name' => 'RankPro', 'url' => url('/')],
            'custom_schema' => [],
            'public' => true,
        ];
        $siteName = 'RankPro';
        $locale = 'en_IN';
    }
@endphp
    <title>{{ $meta['title'] }}</title>
    <meta name="description" content="{{ $meta['description'] }}">
    <meta name="keywords" content="{{ $meta['keywords'] }}">
    <meta name="robots" content="{{ $meta['robots'] }}">
    <meta name="author" content="{{ $siteName }}">
    <link rel="canonical" href="{{ $meta['canonical'] }}">
@if(!empty($meta['public']))
    <link rel="alternate" hreflang="en-in" href="{{ $meta['canonical'] }}">
    <link rel="alternate" hreflang="en" href="{{ $meta['canonical'] }}">
    <link rel="alternate" hreflang="x-default" href="{{ $meta['canonical'] }}">
@endif

    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="{{ $locale }}">
    <meta property="og:type" content="{{ $meta['og_type'] }}">
    <meta property="og:title" content="{{ $meta['title'] }}">
    <meta property="og:description" content="{{ $meta['description'] }}">
    <meta property="og:url" content="{{ $meta['canonical'] }}">
    <meta property="og:image" content="{{ $meta['image'] }}">
    <meta property="og:image:alt" content="{{ $meta['title'] }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $meta['title'] }}">
    <meta name="twitter:description" content="{{ $meta['description'] }}">
    <meta name="twitter:image" content="{{ $meta['image'] }}">

    <script type="application/ld+json">{!! json_encode($meta['schema'], $jsonFlags) !!}</script>
@foreach(($meta['custom_schema'] ?? []) as $node)
    <script type="application/ld+json">{!! json_encode($node, $jsonFlags) !!}</script>
@endforeach
