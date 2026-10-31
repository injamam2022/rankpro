<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoursePackage;
use App\Models\CoursePackageFeature;
use App\Models\CoursePackageMode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CoursePackageController extends Controller
{
    public function list()
    {
        $data['list'] = CoursePackage::withCount(['features', 'modes', 'orders'])
            ->orderBy('sort_order')
            ->get();

        return view('admin.course_package.list', $data);
    }

    public function add()
    {
        return view('admin.course_package.add');
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:190',
            'code' => 'required|string|max:80|unique:course_packages,code',
        ]);

        $package = CoursePackage::create($this->packagePayload($request));
        $this->syncFeatures($package, $request->input('features', []));
        $this->syncModes($package, $request->input('modes', []));

        toastr()->success('Course package added successfully.');
        return redirect()->route('admin.course_package');
    }

    public function edit(Request $request)
    {
        $data['details'] = CoursePackage::with(['features', 'modes'])->findOrFail($request->id);
        return view('admin.course_package.edit', $data);
    }

    public function update(Request $request)
    {
        $package = CoursePackage::findOrFail($request->id);

        $request->validate([
            'name' => 'required|string|max:190',
            'code' => 'required|string|max:80|unique:course_packages,code,' . $package->id,
        ]);

        $package->update($this->packagePayload($request));
        $this->syncFeatures($package, $request->input('features', []));
        $this->syncModes($package, $request->input('modes', []));

        toastr()->success('Course package updated successfully.');
        return redirect()->route('admin.course_package');
    }

    public function delete(Request $request)
    {
        CoursePackage::where('id', $request->id)->delete();
        toastr()->success('Course package deleted successfully.');
        return redirect()->route('admin.course_package');
    }

    public function change(Request $request)
    {
        $package = CoursePackage::findOrFail($request->id);
        $package->update(['status' => $package->status ? 0 : 1]);
        toastr()->success('Status updated.');
        return redirect()->route('admin.course_package');
    }

    private function packagePayload(Request $request): array
    {
        return [
            'code' => $request->code,
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'tagline' => $request->tagline,
            'description' => $request->description,
            'theme' => $request->theme ?: 'blue',
            'display_price' => $request->display_price ?: 0,
            'tax' => $request->tax ?: 0,
            'is_free' => (int) $request->boolean('is_free'),
            'includes_online' => (int) $request->boolean('includes_online'),
            'includes_omr' => (int) $request->boolean('includes_omr'),
            'mode_selection' => $request->mode_selection ?: 'fixed',
            'schedule_scope' => $request->schedule_scope,
            'sort_order' => (int) ($request->sort_order ?: 0),
            'status' => (int) $request->status,
        ];
    }

    private function syncFeatures(CoursePackage $package, array $features): void
    {
        $package->features()->delete();
        $i = 1;
        foreach ($features as $text) {
            $text = trim((string) $text);
            if ($text === '') {
                continue;
            }
            CoursePackageFeature::create([
                'course_package_id' => $package->id,
                'text' => $text,
                'sort_order' => $i++,
            ]);
        }
    }

    private function syncModes(CoursePackage $package, array $modes): void
    {
        $package->modes()->delete();
        $i = 1;
        foreach ($modes as $mode) {
            if (empty($mode['mode_code']) || empty($mode['label'])) {
                continue;
            }
            CoursePackageMode::create([
                'course_package_id' => $package->id,
                'mode_code' => $mode['mode_code'],
                'label' => $mode['label'],
                'price' => $mode['price'] ?? 0,
                'requires_location' => !empty($mode['requires_location']),
                'requires_address' => !empty($mode['requires_address']),
                'includes_online' => !empty($mode['includes_online']),
                'includes_omr' => !empty($mode['includes_omr']),
                'help_text' => $mode['help_text'] ?? null,
                'sort_order' => $i++,
                'status' => 1,
            ]);
        }
    }
}
