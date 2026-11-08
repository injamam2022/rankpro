<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Footer;
use App\Support\WebsiteSettings;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function list()
    {
        return view('admin.footer.settings', [
            'settings' => WebsiteSettings::all(),
            'groups' => WebsiteSettings::groups(),
        ]);
    }

    public function saveSettings(Request $request)
    {
        $rules = [];
        foreach (WebsiteSettings::defaults() as $key => $meta) {
            $type = $meta['type'] ?? 'text';
            if ($type === 'email') {
                $rules[$key] = 'nullable|email|max:190';
            } elseif ($type === 'textarea') {
                $rules[$key] = 'nullable|string|max:2000';
            } else {
                $rules[$key] = 'nullable|string|max:500';
            }
        }

        $validated = $request->validate($rules);
        $socialKeys = array_keys(WebsiteSettings::fieldsIn('social'));

        foreach (WebsiteSettings::defaults() as $key => $meta) {
            $value = trim((string) ($validated[$key] ?? ''));
            if (in_array($key, $socialKeys, true)) {
                $value = WebsiteSettings::normalizeUrl($value);
            }

            Footer::updateOrCreate(
                ['key_name' => $key],
                [
                    'name' => $meta['name'],
                    'value' => $value,
                    'status' => 1,
                ]
            );
        }

        $phone = trim((string) ($validated['phone'] ?? ''));
        if ($phone !== '') {
            Footer::updateOrCreate(
                ['key_name' => 'contact_us'],
                [
                    'name' => 'Contact Us',
                    'value' => $phone,
                    'status' => 1,
                ]
            );
        }

        WebsiteSettings::flush();
        toastr()->success('Website settings saved successfully.');

        return redirect()->route('admin.website_settings');
    }

    public function add()
    {
        return view('admin.footer.add');
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Footer::create([
            'name' => $request->name,
            'key_name' => $request->key_name,
            'value' => $request->value,
            'status' => $request->status,
        ]);
        WebsiteSettings::flush();
        toastr()->success('Footer added successfully.');

        return redirect()->route('admin.website_settings');
    }

    public function edit(Request $request)
    {
        return view('admin.footer.edit', [
            'details' => Footer::where('id', $request->id)->first(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $row = Footer::where('id', $request->id)->first();
        if (!$row) {
            toastr()->warning('Setting not found.');
            return back()->withInput();
        }

        $row->update([
            'name' => $request->name,
            'value' => $request->value,
            'status' => $request->status,
        ]);
        WebsiteSettings::flush();
        toastr()->success('Website setting updated successfully.');

        return redirect()->route('admin.website_settings');
    }

    public function delete(Request $request)
    {
        Footer::where('id', $request->id)->delete();
        WebsiteSettings::flush();
        toastr()->success('Website setting deleted successfully.');

        return redirect()->route('admin.website_settings');
    }

    public function change(Request $request)
    {
        $row = Footer::where('id', $request->id)->first();
        if ($row) {
            $row->update(['status' => $row->status ? 0 : 1]);
        }

        WebsiteSettings::flush();
        toastr()->success('Website setting status changed successfully.');

        return redirect()->route('admin.website_settings');
    }
}
