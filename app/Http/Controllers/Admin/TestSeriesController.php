<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use App\Models\TestSeries;
use App\Models\TestSeriesDescription;
use App\Models\TestSeriesHeading;
use App\Models\TestSeriesAbout;
use App\Models\TestSeriesOverview;
use App\Models\TestSeriesExamdesc;
use App\Models\TestSeriesMarkscheme;
use App\Models\Language;
use App\Models\TestSeriesLanguage;
use App\Models\TestSeriesLocation;
use App\Models\TestSeriesDate;
use App\Models\Subject;

class TestSeriesController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = TestSeries::with(['headings' => function ($query) {
                            $query->select('test_series_id', 'language', 'heading')
                                ->orderBy('id', 'asc');
                        },'descriptions' => function ($query) {
                            $query->select('test_series_id', 'language', 'text')
                                ->orderBy('id', 'asc');
                        }])
                        ->get();
            // dd($data['list'][0]->headings);
        return view('admin.cms.test_series.list',$data);
    }

    public function add(){
        $data = [];
        $data['languages'] = Language::where('status',1)->get();
        $data['subjects'] = Subject::where('status','1')->where('is_deleted','0')->get();
        return view('admin.cms.test_series.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                        ]);

        $input = $request->all();

        $insertData = [];
        if ($image = $request->file('image')){
            $insertData['image'] = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/test_series/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$insertData['image']);


            $destinationPath = public_path('/uploads/test_series');
            $image->move($destinationPath, $insertData['image']);
        }
        if ($image = $request->file('icon')){
            $insertData['icon'] = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/test_series/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$insertData['icon']);


            $destinationPath = public_path('/uploads/test_series');
            $image->move($destinationPath, $insertData['icon']);
        }
        $insertData['name'] = $request->name;
        $insertData['subjects'] = implode(',', $request->subjects);
        $insertData['price'] = $request->price;
        $insertData['dis_price'] = $request->dis_price;
        $insertData['tax'] = $request->tax;
        $insertData['status'] = $request->status;

        $test_series = TestSeries::create($insertData);

        if ($request->language_name && is_array($request->language_name)) {
            foreach ($request->language_name as $languageName) {
                if (!empty($languageName)) {
                    TestSeriesLanguage::create([
                        'test_series_id' => $test_series->id,
                        'language_name' => $languageName
                    ]);
                }
            }
        }

        if ($request->location_name && is_array($request->location_name)) {
            foreach ($request->location_name as $locationName) {
                if (!empty($locationName)) {
                    TestSeriesLocation::create([
                        'test_series_id' => $test_series->id,
                        'location_name' => $locationName
                    ]);
                }
            }
        }

        if ($request->date_name && is_array($request->date_name)) {
            foreach ($request->date_name as $dateName) {
                if (!empty($dateName)) {
                    TestSeriesDate::create([
                        'test_series_id' => $test_series->id,
                        'date_name' => $dateName
                    ]);
                }
            }
        }

        $fields = [
            'text' => TestSeriesDescription::class,
            'heading' => TestSeriesHeading::class,
            'about' => TestSeriesAbout::class,
            'overview' => TestSeriesOverview::class,
            'examdesc' => TestSeriesExamdesc::class,
            'markscheme' => TestSeriesMarkscheme::class,
        ];
        foreach ($request->all() as $key => $value) {
            foreach ($fields as $prefix => $model) {
                if (strpos($key, $prefix . '_') === 0 && !empty($value)) {
                    $languageId = substr($key, strlen($prefix) + 1);

                    $language = Language::find($languageId);
                    if ($language) {
                        $model::create([
                            'test_series_id' => $test_series->id,
                            'language' => $language->id,
                            $prefix => $value,
                        ]);
                    }
                }
            }
        }

        toastr()->success('TestSeries added successfully.');
        return redirect()->route('admin.test_series');
    }

    public function edit(Request $request)
    {
        $test_series = TestSeries::with([
                                            'descriptions',
                                            'headings',
                                            'abouts',
                                            'overviews',
                                            'examdescs',
                                            'markschemes',
                                            'language',
                                            'location',
                                            'date'
                                        ])->findOrFail($request->id);
        $languages = Language::all();
        $subjects = Subject::where('status','1')->where('is_deleted','0')->get();

        return view('admin.cms.test_series.edit', compact('test_series', 'languages', 'subjects'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $test_series = TestSeries::findOrFail($request->id);
        $updateData = [];

        if ($image = $request->file('image')) {
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/test_series/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$imageName);

            $destinationPath = public_path('/uploads/test_series');
            $image->move($destinationPath, $imageName);
            $updateData['image'] = $imageName;

            if ($test_series->image) {
                $thumbnailPath = public_path('uploads/test_series/thumbnail/' . $test_series->image);
                $imagePath = public_path('uploads/test_series/' . $test_series->image);

                if (file_exists($thumbnailPath)) {
                    unlink($thumbnailPath);
                }

                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }

        if ($image = $request->file('icon')) {
            $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/test_series/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$imageName);

            $destinationPath = public_path('/uploads/test_series');
            $image->move($destinationPath, $imageName);
            $updateData['icon'] = $imageName;

            if ($test_series->icon) {
                $thumbnailPath = public_path('uploads/test_series/thumbnail/' . $test_series->icon);
                $imagePath = public_path('uploads/test_series/' . $test_series->icon);

                if (file_exists($thumbnailPath)) {
                    unlink($thumbnailPath);
                }

                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
        $updateData['name'] = $request->name;
        $updateData['subjects'] = implode(',', $request->subjects);
        $updateData['status'] = $request->status;
        $updateData['price'] = $request->price;
        $updateData['dis_price'] = $request->dis_price;
        $updateData['tax'] = $request->tax;
        $test_series->update($updateData);

        $fields = [
            'text' => TestSeriesDescription::class,
            'heading' => TestSeriesHeading::class,
            'about' => TestSeriesAbout::class,
            'overview' => TestSeriesOverview::class,
            'examdesc' => TestSeriesExamdesc::class,
            'markscheme' => TestSeriesMarkscheme::class,
        ];

        foreach ($request->all() as $key => $value) {
            foreach ($fields as $prefix => $model) {
                if (strpos($key, $prefix . '_') === 0 && !empty($value)) {
                    $languageId = substr($key, strlen($prefix) + 1);
                    $language = Language::find($languageId);

                    if ($language) {
                        $existing = $model::where('test_series_id', $test_series->id)
                            ->where('language', $languageId)
                            ->first();

                        if ($existing) {
                            $existing->update([
                                $prefix => $value,
                            ]);
                        } else {
                            $model::create([
                                'test_series_id' => $test_series->id,
                                'language' => $languageId,
                                $prefix => $value,
                            ]);
                        }
                    }
                }
            }
        }

        TestSeriesLanguage::where('test_series_id', $test_series->id)->delete();
        TestSeriesLocation::where('test_series_id', $test_series->id)->delete();
        TestSeriesDate::where('test_series_id', $test_series->id)->delete();

        if ($request->language_name && is_array($request->language_name)) {
            foreach ($request->language_name as $languageName) {
                if (!empty($languageName)) {
                    TestSeriesLanguage::create([
                        'test_series_id' => $test_series->id,
                        'language_name' => $languageName,
                    ]);
                }
            }
        }

        if ($request->location_name && is_array($request->location_name)) {
            foreach ($request->location_name as $locationName) {
                if (!empty($locationName)) {
                    TestSeriesLocation::create([
                        'test_series_id' => $test_series->id,
                        'location_name' => $locationName,
                    ]);
                }
            }
        }

        if ($request->date_name && is_array($request->date_name)) {
            foreach ($request->date_name as $dateName) {
                if (!empty($dateName)) {
                    TestSeriesDate::create([
                        'test_series_id' => $test_series->id,
                        'date_name' => $dateName,
                    ]);
                }
            }
        }

        toastr()->success('TestSeries updated successfully.');
        return redirect()->route('admin.test_series');
    }

    public function delete(Request $request){
        $id = $request->id;
        $loginCheck = TestSeries::find($id);

        if($loginCheck){
            if ($loginCheck->image && file_exists(public_path('uploads/test_series/' . $loginCheck->image))) {
                unlink(public_path('uploads/test_series/' . $loginCheck->image));
                unlink(public_path('uploads/test_series/thumbnail/' . $loginCheck->image));
            }
            $loginCheck->delete();
            toastr()->success('TestSeries deleted successfully.');
        } else {
            toastr()->error('TestSeries not found.');
        }
        return redirect()->route('admin.test_series');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = TestSeries::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('TestSeries status change successfully.');
        return redirect()->route('admin.test_series');
    }
}
