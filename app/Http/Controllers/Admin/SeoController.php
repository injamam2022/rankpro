<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoPage;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class SeoController extends Controller
{
    public function list()
    {
        $data = [];
        $data['pages'] = Seo::pages();
        $data['overrides'] = Seo::overrides();
        return view('admin.seo.list', $data);
    }

    public function edit(Request $request)
    {
        $pages = Seo::pages();
        $route = (string) $request->route_name;
        if (!isset($pages[$route])) {
            toastr()->warning('Unknown page.');
            return redirect()->route('admin.seo');
        }

        $data = [];
        $data['route_name'] = $route;
        $data['page'] = $pages[$route];
        $data['details'] = SeoPage::where('route_name', $route)->first();
        $data['url'] = $this->exampleUrl($route);
        return view('admin.seo.edit', $data);
    }

    public function update(Request $request)
    {
        $pages = Seo::pages();
        $route = (string) $request->route_name;
        if (!isset($pages[$route])) {
            toastr()->warning('Unknown page.');
            return redirect()->route('admin.seo');
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'keywords' => 'nullable|string|max:1000',
            'robots' => 'nullable|string|max:50',
            'og_image' => 'nullable|url|max:255',
            'schema_json' => ['nullable', 'string', function ($attribute, $value, $fail) {
                json_decode($value, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $fail('Custom schema must be valid JSON: ' . json_last_error_msg());
                }
            }],
        ]);

        SeoPage::updateOrCreate(
            ['route_name' => $route],
            [
                'title' => trim((string) $request->title) ?: null,
                'description' => trim((string) $request->description) ?: null,
                'keywords' => trim((string) $request->keywords) ?: null,
                'robots' => trim((string) $request->robots) ?: null,
                'og_image' => trim((string) $request->og_image) ?: null,
                'schema_json' => trim((string) $request->schema_json) ?: null,
            ]
        );

        Seo::flush();
        toastr()->success('SEO for ' . $pages[$route]['label'] . ' saved successfully.');
        return redirect()->route('admin.seo');
    }

    public function reset(Request $request)
    {
        SeoPage::where('route_name', (string) $request->route_name)->delete();
        Seo::flush();
        toastr()->success('SEO reset to defaults.');
        return redirect()->route('admin.seo');
    }

    private function exampleUrl(string $route): string
    {
        if (!Route::has($route)) {
            return '';
        }

        $params = Route::getRoutes()->getByName($route)->parameterNames();
        return $params ? '' : route($route);
    }
}
