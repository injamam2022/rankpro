<?php

namespace App\Support;

use App\Models\SeoPage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Throwable;

class Seo
{
    public const CACHE_KEY = 'rankpro.seo_pages';
    public const SITE_NAME = 'RankPro';
    public const LOCALE = 'en_IN';
    public const LANGUAGE = 'en-IN';

    private const KEYWORDS = 'NEET mock test, NEET test series, NEET 2027, free NEET mock test, NEET online test, NEET preparation, NEET PYQ, AI NEET analysis, NEET mentorship, RankPro';

    /**
     * Public, indexable pages. Any route not listed here is treated as a private
     * (logged-in) page and rendered with noindex.
     */
    public static function pages(): array
    {
        return [
            'index' => [
                'label' => 'Home',
                'type' => 'WebPage',
                'title' => 'NEET Mock Tests & AI Test Series 2027 | RankPro',
                'description' => 'Prepare for NEET with RankPro: unlimited free AI-powered mock tests, real exam-pattern test series, instant analysis and one-on-one mentorship from top NEET rankers.',
                'keywords' => self::KEYWORDS,
            ],
            'testseries' => [
                'label' => 'NEET Test Series',
                'type' => 'CollectionPage',
                'title' => 'NEET Test Series 2027 – Full-Length Mock Tests | RankPro',
                'description' => 'Join the RankPro NEET test series: full-length tests on the latest NTA pattern, same-day results, AIR prediction and chapter-wise analysis for Class 11, 12 and droppers.',
                'keywords' => 'NEET test series, NEET full length mock test, NEET 2027 test series, NEET online test series, NEET AIR prediction, RankPro test series',
            ],
            'testseries.details' => [
                'label' => 'Test Series detail',
                'type' => 'WebPage',
                'title' => '{name} – NEET Test Series | RankPro',
                'description' => '{name}: NEET-pattern test series by RankPro with real exam simulation, detailed solutions, performance analytics and AIR prediction.',
                'keywords' => 'NEET test series, NEET mock test, {name}, RankPro',
            ],
            'ranker' => [
                'label' => 'Rankers (Mentorship)',
                'type' => 'CollectionPage',
                'title' => 'Learn from NEET Toppers – 1:1 Mentorship | RankPro',
                'description' => 'Book one-on-one mentorship with students who cracked NEET. Get study plans, exam strategy and doubt solving from top NEET rankers on RankPro.',
                'keywords' => 'NEET mentorship, NEET topper mentor, NEET ranker guidance, NEET counselling, one on one NEET mentor, RankPro rankers',
            ],
            'ranker.detail' => [
                'label' => 'Ranker detail',
                'type' => 'ProfilePage',
                'title' => '{name} – NEET Ranker Mentor | RankPro',
                'description' => 'Get one-on-one NEET mentorship from {name} on RankPro: personalised strategy, study plans and doubt solving from a NEET ranker.',
                'keywords' => 'NEET mentor, NEET ranker, {name}, NEET mentorship, RankPro',
            ],
            'new_light' => [
                'label' => 'New Light',
                'type' => 'CollectionPage',
                'title' => 'New Light NEET Programme | RankPro',
                'description' => 'Explore the RankPro New Light programme for NEET aspirants: structured tests, subject-wise practice and expert guidance to boost your score.',
                'keywords' => 'NEET programme, NEET course, NEET practice tests, RankPro New Light',
            ],
            'new_light.details' => [
                'label' => 'New Light detail',
                'type' => 'WebPage',
                'title' => '{name} – New Light Programme | RankPro',
                'description' => '{name}: part of the RankPro New Light NEET programme with subject-wise tests, solutions and performance analysis.',
                'keywords' => 'NEET programme, {name}, RankPro New Light',
            ],
            'about' => [
                'label' => 'About Us',
                'type' => 'AboutPage',
                'title' => 'About RankPro – AI-Powered NEET Preparation',
                'description' => 'RankPro helps NEET aspirants practise smarter with AI-driven mock tests, real exam patterns, detailed analytics and mentorship from top rankers.',
                'keywords' => 'about RankPro, NEET preparation platform, NEET edtech, AI NEET mock test',
            ],
            'contact' => [
                'label' => 'Contact Us',
                'type' => 'ContactPage',
                'title' => 'Contact RankPro – NEET Test Series Support',
                'description' => 'Contact RankPro on call, WhatsApp or email for help with NEET test series, payments, mentorship and your account.',
                'keywords' => 'contact RankPro, RankPro support, RankPro phone number, NEET test series help',
            ],
            'help' => [
                'label' => 'Help & Support',
                'type' => 'WebPage',
                'title' => 'Help & Support | RankPro',
                'description' => 'Find answers about RankPro NEET mock tests, test series, payments and accounts, or reach our support team on call and WhatsApp.',
                'keywords' => 'RankPro help, RankPro support, NEET test series FAQ',
            ],
            'blog' => [
                'label' => 'Blog',
                'type' => 'CollectionPage',
                'title' => 'NEET Preparation Blog – Tips & Strategy | RankPro',
                'description' => 'NEET preparation tips, exam strategy, syllabus updates and topper advice from the RankPro team.',
                'keywords' => 'NEET preparation tips, NEET strategy, NEET syllabus, NEET blog, RankPro blog',
            ],
            'blog.show' => [
                'label' => 'Blog post',
                'type' => 'Article',
                'title' => '{name} | RankPro Blog',
                'description' => '{name} – NEET strategy, tips and RankPro updates.',
                'keywords' => 'NEET preparation tips, NEET strategy, {name}, RankPro blog',
            ],
            'careers' => [
                'label' => 'Careers',
                'type' => 'WebPage',
                'title' => 'Careers at RankPro – Join Our EdTech Team',
                'description' => 'Work with RankPro to help NEET aspirants succeed. See open roles in teaching, content, technology and growth.',
                'keywords' => 'RankPro careers, edtech jobs, NEET faculty jobs, RankPro hiring',
            ],
            'privacy' => [
                'label' => 'Privacy Policy',
                'type' => 'WebPage',
                'title' => 'Privacy Policy | RankPro',
                'description' => 'How RankPro collects, uses and protects your personal information.',
                'keywords' => 'RankPro privacy policy',
            ],
            'terms' => [
                'label' => 'Terms & Conditions',
                'type' => 'WebPage',
                'title' => 'Terms & Conditions | RankPro',
                'description' => 'Terms and conditions for using RankPro NEET mock tests, test series and mentorship services.',
                'keywords' => 'RankPro terms and conditions',
            ],
            'signup' => [
                'label' => 'Sign Up',
                'type' => 'WebPage',
                'title' => 'Sign Up Free – Start NEET Mock Tests | RankPro',
                'description' => 'Create your free RankPro account and start NEET mock tests with instant AI analysis. Your first 3 custom tests are free.',
                'keywords' => 'RankPro sign up, free NEET mock test, NEET online test registration',
            ],
            'login' => [
                'label' => 'Login',
                'type' => 'WebPage',
                'title' => 'Login | RankPro',
                'description' => 'Log in to RankPro to continue your NEET mock tests, analysis and mentorship.',
                'keywords' => 'RankPro login',
                'robots' => 'noindex, follow',
            ],
        ];
    }

    public static function overrides(): array
    {
        try {
            return Cache::remember(self::CACHE_KEY, 600, function () {
                return SeoPage::query()->get()->keyBy('route_name')->map(function ($row) {
                    return $row->toArray();
                })->toArray();
            });
        } catch (Throwable $e) {
            return [];
        }
    }

    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Resolve all meta for the current request.
     *
     * $overrides may set: title, description, keywords, robots, image, type,
     * name (fills {name} placeholders), schema (extra JSON-LD nodes).
     */
    public static function resolve(array $overrides = []): array
    {
        $route = optional(Route::current())->getName() ?? '';
        $pages = self::pages();
        $isPublic = isset($pages[$route]);
        $page = $pages[$route] ?? [];
        $stored = self::overrides()[$route] ?? [];

        $pick = function (string $key, ?string $storedKey = null) use ($overrides, $stored, $page) {
            $storedKey = $storedKey ?? $key;
            foreach ([$overrides[$key] ?? null, $stored[$storedKey] ?? null, $page[$key] ?? null] as $value) {
                if (is_string($value) && trim($value) !== '') {
                    return trim($value);
                }
            }
            return '';
        };

        $name = trim(strip_tags((string) ($overrides['name'] ?? '')));
        $fill = function (string $text) use ($name) {
            $text = str_replace('{name}', $name !== '' ? $name : self::SITE_NAME, $text);
            return trim(preg_replace('/\s+/', ' ', $text));
        };

        $title = $fill($pick('title') ?: self::pages()['index']['title']);
        $description = $fill($pick('description') ?: self::pages()['index']['description']);
        $keywords = $fill($pick('keywords') ?: self::KEYWORDS);
        $robots = $pick('robots') ?: ($isPublic ? 'index, follow, max-image-preview:large, max-snippet:-1' : 'noindex, nofollow');
        $image = $pick('image', 'og_image') ?: asset('web/images/logo.png');
        $type = $overrides['type'] ?? ($page['type'] ?? 'WebPage');
        $canonical = url()->current();

        $schema = self::schemaGraph([
            'route' => $route,
            'type' => $type,
            'title' => $title,
            'description' => Str::limit($description, 300, ''),
            'url' => $canonical,
            'image' => $image,
            'label' => $name !== '' ? $name : ($page['label'] ?? $title),
            'public' => $isPublic,
        ], $overrides['schema'] ?? []);

        $custom = [];
        if (!empty($stored['schema_json'])) {
            $decoded = json_decode($stored['schema_json'], true);
            if (is_array($decoded)) {
                $custom[] = $decoded;
            }
        }

        return [
            'title' => Str::limit($title, 70, ''),
            'description' => Str::limit($description, 165, ''),
            'keywords' => $keywords,
            'robots' => $robots,
            'canonical' => $canonical,
            'image' => $image,
            'og_type' => $route === 'index' ? 'website' : 'article',
            'schema' => $schema,
            'custom_schema' => $custom,
            'public' => $isPublic,
        ];
    }

    private static function schemaGraph(array $p, array $extra): array
    {
        $home = url('/');
        $orgId = $home . '/#organization';
        $siteId = $home . '/#website';
        $pageId = $p['url'] . '#webpage';

        $sameAs = array_values(array_filter([
            WebsiteSettings::get('facebook'),
            WebsiteSettings::get('instagram'),
            WebsiteSettings::get('linkedin'),
            WebsiteSettings::get('youtube'),
        ]));

        $organization = [
            '@type' => ['EducationalOrganization', 'Organization'],
            '@id' => $orgId,
            'name' => self::SITE_NAME,
            'url' => $home,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => asset('web/images/logo.png'),
            ],
            'email' => WebsiteSettings::get('email'),
            'telephone' => '+' . WebsiteSettings::digits('phone'),
            'contactPoint' => [[
                '@type' => 'ContactPoint',
                'telephone' => '+' . WebsiteSettings::digits('phone'),
                'email' => WebsiteSettings::get('email'),
                'contactType' => 'customer support',
                'areaServed' => 'IN',
                'availableLanguage' => ['English', 'Hindi', 'Bengali'],
            ]],
        ];
        $address = trim(strip_tags(WebsiteSettings::get('address')));
        if ($address !== '') {
            $organization['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $address,
                'addressCountry' => 'IN',
            ];
        }
        if ($sameAs) {
            $organization['sameAs'] = $sameAs;
        }

        $graph = [
            $organization,
            [
                '@type' => 'WebSite',
                '@id' => $siteId,
                'url' => $home,
                'name' => self::SITE_NAME,
                'description' => self::pages()['index']['description'],
                'publisher' => ['@id' => $orgId],
                'inLanguage' => self::LANGUAGE,
            ],
        ];

        $webPage = [
            '@type' => $p['type'],
            '@id' => $pageId,
            'url' => $p['url'],
            'name' => $p['title'],
            'description' => $p['description'],
            'isPartOf' => ['@id' => $siteId],
            'about' => ['@id' => $orgId],
            'inLanguage' => self::LANGUAGE,
            'primaryImageOfPage' => ['@type' => 'ImageObject', 'url' => $p['image']],
        ];

        if ($p['route'] !== 'index') {
            $crumbs = [['name' => 'Home', 'item' => $home]];
            $parents = ['testseries.details' => 'testseries', 'ranker.detail' => 'ranker', 'new_light.details' => 'new_light', 'blog.show' => 'blog'];
            if (isset($parents[$p['route']]) && Route::has($parents[$p['route']])) {
                $parent = $parents[$p['route']];
                $crumbs[] = ['name' => self::pages()[$parent]['label'], 'item' => route($parent)];
            }
            $crumbs[] = ['name' => $p['label'], 'item' => $p['url']];

            $graph[] = [
                '@type' => 'BreadcrumbList',
                '@id' => $p['url'] . '#breadcrumb',
                'itemListElement' => array_map(function ($crumb, $i) {
                    return ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $crumb['name'], 'item' => $crumb['item']];
                }, $crumbs, array_keys($crumbs)),
            ];
            $webPage['breadcrumb'] = ['@id' => $p['url'] . '#breadcrumb'];
        }

        $graph[] = $webPage;

        foreach ($extra as $node) {
            if (is_array($node) && $node) {
                $graph[] = $node;
            }
        }

        return ['@context' => 'https://schema.org', '@graph' => $graph];
    }

    public static function faqSchema($questions): array
    {
        $items = [];
        foreach ($questions ?? [] as $q) {
            $question = trim(html_entity_decode(strip_tags((string) ($q->text ?? ''))));
            $answer = trim(html_entity_decode(strip_tags((string) ($q->text2 ?? ''))));
            if ($question !== '' && $answer !== '') {
                $items[] = [
                    '@type' => 'Question',
                    'name' => $question,
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $answer],
                ];
            }
        }

        return $items ? ['@type' => 'FAQPage', '@id' => url('/') . '/#faq', 'mainEntity' => $items] : [];
    }

    public static function courseSchema($test): array
    {
        if (!$test) {
            return [];
        }

        $price = (float) ($test->dis_price ?: $test->price);
        $node = [
            '@type' => 'Course',
            'name' => strip_tags((string) $test->name),
            'description' => Str::limit(trim(strip_tags((string) ($test->description ?: $test->name))), 300, ''),
            'url' => url()->current(),
            'inLanguage' => self::LANGUAGE,
            'educationalLevel' => 'NEET UG',
            'provider' => ['@id' => url('/') . '/#organization'],
            'hasCourseInstance' => [
                '@type' => 'CourseInstance',
                'courseMode' => 'Online',
                'courseWorkload' => 'PT' . max(1, (int) ($test->total_time_for_exam ?: 180)) . 'M',
            ],
        ];

        if ($price > 0) {
            $node['offers'] = [
                '@type' => 'Offer',
                'category' => 'Paid',
                'price' => number_format($price, 2, '.', ''),
                'priceCurrency' => 'INR',
                'availability' => 'https://schema.org/InStock',
                'url' => url()->current(),
            ];
        }

        return $node;
    }

    public static function personSchema($ranker, string $image = ''): array
    {
        if (!$ranker) {
            return [];
        }

        $node = [
            '@type' => 'Person',
            'name' => strip_tags((string) $ranker->name),
            'jobTitle' => 'NEET Mentor',
            'description' => Str::limit(trim(strip_tags((string) ($ranker->about ?: $ranker->description))), 300, ''),
            'url' => url()->current(),
            'worksFor' => ['@id' => url('/') . '/#organization'],
        ];
        if ($image !== '') {
            $node['image'] = $image;
        }

        return $node;
    }
}
