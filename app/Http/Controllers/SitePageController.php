<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Contact;
use App\Support\Seo;
use App\Support\WebsiteSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SitePageController extends Controller
{
    public function about()
    {
        return view('site.page', [
            'title' => 'About Us',
            'heading' => 'About RankPro',
            'body' => view('site.pages.about')->render(),
        ]);
    }

    public function privacy()
    {
        return view('site.page', [
            'title' => 'Privacy Policy',
            'heading' => 'Privacy Policy',
            'body' => view('site.pages.privacy')->render(),
        ]);
    }

    public function terms()
    {
        return view('site.page', [
            'title' => 'Terms and Conditions',
            'heading' => 'Terms and Conditions',
            'body' => view('site.pages.terms')->render(),
        ]);
    }

    public function careers()
    {
        return view('site.page', [
            'title' => 'Careers',
            'heading' => 'Careers at RankPro',
            'body' => view('site.pages.careers', [
                'email' => WebsiteSettings::get('email'),
            ])->render(),
        ]);
    }

    public function help()
    {
        return view('site.page', [
            'title' => 'Help & Support',
            'heading' => 'Help & Support',
            'body' => view('site.pages.help', [
                'email' => WebsiteSettings::get('email'),
                'phone' => WebsiteSettings::displayPhone('phone'),
                'whatsapp' => WebsiteSettings::displayPhone('whatsapp'),
                'tel' => WebsiteSettings::telHref('phone'),
                'wa' => WebsiteSettings::whatsappHref(),
            ])->render(),
        ]);
    }

    public function scholarship()
    {
        return view('site.scholarship');
    }

    public function scholarshipRegister(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{8,20}$/'],
            'class' => ['required', 'in:Class 10,Class 10+2 (Class 11 / 12),Class 12+ (12th pass & beyond)'],
            'city' => ['nullable', 'string', 'max:80'],
            'mode' => ['required', 'in:Online,Offline'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['nullable', 'string', 'max:500'],
        ], [
            'phone.regex' => 'Enter a valid phone number.',
            'class.required' => 'Select your class.',
            'class.in' => 'Select your class.',
        ]);

        $city = trim((string) ($data['city'] ?? ''));
        $note = trim((string) ($data['message'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));
        $summary = 'Scholarship Test | Class: '.$data['class']
            .' | City: '.($city !== '' ? $city : '-')
            .' | Mode: '.$data['mode'];
        if ($note !== '') {
            $summary .= ' | Note: '.$note;
        }

        Contact::create([
            'page_type' => 'scholarship',
            'name' => $data['name'],
            'email' => $email,
            'mobile_number' => $data['phone'],
            'message' => Str::limit($summary, 255, ''),
            'status' => 1,
        ]);

        $whatsapp = 'https://wa.me/'.WebsiteSettings::digits('whatsapp').'?text='.rawurlencode(implode("\n", [
            'Scholarship Test Registration',
            'Name: '.$data['name'],
            'Phone: '.$data['phone'],
            'Class: '.$data['class'],
            'City: '.($city !== '' ? $city : '-'),
            'Mode: '.$data['mode'],
            'Email: '.($email !== '' ? $email : '-'),
            'Message: '.($note !== '' ? $note : '-'),
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'whatsapp' => $whatsapp,
            ]);
        }

        return redirect()
            ->to(route('scholarship').'#register')
            ->with('scholarship_ok', true)
            ->with('scholarship_wa', $whatsapp);
    }

    public function blog()
    {
        $posts = Blog::published()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(9);

        return view('site.blog.index', [
            'title' => 'Blog',
            'posts' => $posts,
        ]);
    }

    public function blogShow(string $slug)
    {
        $post = Blog::published()->where('slug', $slug)->firstOrFail();

        return view('site.blog.show', [
            'post' => $post,
        ]);
    }

    public function sitemap()
    {
        $urls = [];

        foreach (array_keys(Seo::pages()) as $routeName) {
            if (!Route::has($routeName)) {
                continue;
            }

            $route = Route::getRoutes()->getByName($routeName);
            if (!$route || count($route->parameterNames()) > 0) {
                continue;
            }

            $urls[] = [
                'loc' => route($routeName),
                'changefreq' => $routeName === 'index' ? 'daily' : 'weekly',
                'priority' => $routeName === 'index' ? '1.0' : '0.7',
            ];
        }

        foreach (Blog::published()->orderByDesc('published_at')->get(['slug', 'updated_at', 'published_at']) as $post) {
            $urls[] = [
                'loc' => route('blog.show', $post->slug),
                'lastmod' => optional($post->updated_at ?: $post->published_at)->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ];
        }

        $xml = view('site.sitemap', compact('urls'))->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
