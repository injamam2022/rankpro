<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class BlogController extends Controller
{
    public function list()
    {
        $data = [];
        $data['list'] = Blog::orderByDesc('id')->get();

        return view('admin.cms.blog.list', $data);
    }

    public function add()
    {
        return view('admin.cms.blog.add');
    }

    public function save(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:2000',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'keywords' => 'nullable|string|max:1000',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:0,1',
            'published_at' => 'nullable|date',
        ]);

        $slugSource = filled($request->slug) ? $request->slug : $request->title;

        $insertData = [
            'title' => $request->title,
            'slug' => Blog::uniqueSlug($slugSource),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'keywords' => $this->normalizeKeywords($request->keywords),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description ?: Str::limit(strip_tags((string) $request->excerpt), 160, ''),
            'status' => (int) $request->status,
            'published_at' => $this->resolvePublishedAt($request->published_at),
        ];

        if ($request->hasFile('image')) {
            $insertData['image'] = $this->storeImage($request->file('image'));
        }

        Blog::create($insertData);

        toastr()->success('Blog post added successfully.');
        return redirect()->route('admin.blog');
    }

    public function edit(Request $request)
    {
        $data = [];
        $data['details'] = Blog::findOrFail($request->id);

        return view('admin.cms.blog.edit', $data);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:blogs,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:2000',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'keywords' => 'nullable|string|max:1000',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:0,1',
            'published_at' => 'nullable|date',
        ]);

        $blog = Blog::findOrFail($request->id);
        $slugSource = filled($request->slug) ? $request->slug : $request->title;

        $updateData = [
            'title' => $request->title,
            'slug' => Blog::uniqueSlug($slugSource, $blog->id),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'keywords' => $this->normalizeKeywords($request->keywords),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description ?: Str::limit(strip_tags((string) $request->excerpt), 160, ''),
            'status' => (int) $request->status,
            'published_at' => $this->resolvePublishedAt(
                $request->published_at,
                $blog->published_at
            ),
        ];

        if ($request->hasFile('image')) {
            $this->deleteImageFiles($blog->image);
            $updateData['image'] = $this->storeImage($request->file('image'));
        }

        $blog->update($updateData);

        toastr()->success('Blog post updated successfully.');
        return redirect()->route('admin.blog');
    }

    public function delete(Request $request)
    {
        $blog = Blog::find($request->id);

        if ($blog) {
            $this->deleteImageFiles($blog->image);
            $blog->delete();
            toastr()->success('Blog post deleted successfully.');
        } else {
            toastr()->error('Blog post not found.');
        }

        return redirect()->route('admin.blog');
    }

    public function change(Request $request)
    {
        $blog = Blog::find($request->id);

        if ($blog) {
            $blog->update(['status' => $blog->status ? 0 : 1]);
            toastr()->success('Blog status changed successfully.');
        }

        return redirect()->route('admin.blog');
    }

    private function normalizeKeywords(?string $keywords): ?string
    {
        if ($keywords === null) {
            return null;
        }

        $parts = preg_split('/\s*,\s*/', trim($keywords)) ?: [];
        $parts = array_values(array_filter(array_map('trim', $parts)));

        return $parts ? implode(', ', $parts) : null;
    }

    private function resolvePublishedAt($value, $fallback = null)
    {
        if (filled($value)) {
            try {
                return Carbon::parse($value, config('app.timezone'));
            } catch (\Throwable $e) {
                // fall through
            }
        }

        if ($fallback) {
            return $fallback;
        }

        return now();
    }

    private function storeImage($image): string
    {
        $this->ensureUploadDirs();

        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

        $thumbPath = public_path('uploads/blog/thumbnail');
        $img = Image::make($image->getRealPath());
        $img->resize(400, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($thumbPath . '/' . $filename);

        $image->move(public_path('uploads/blog'), $filename);

        return $filename;
    }

    private function deleteImageFiles(?string $filename): void
    {
        if (!$filename || filter_var($filename, FILTER_VALIDATE_URL)) {
            return;
        }

        $paths = [
            public_path('uploads/blog/' . $filename),
            public_path('uploads/blog/thumbnail/' . $filename),
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                @unlink($path);
            }
        }
    }

    private function ensureUploadDirs(): void
    {
        File::ensureDirectoryExists(public_path('uploads/blog'));
        File::ensureDirectoryExists(public_path('uploads/blog/thumbnail'));
    }
}
