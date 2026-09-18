<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\RankerRules;
use App\Models\Language;

class RankerRulesController extends Controller
{
    public function list(){
        $data = [];
        $rankerRules = RankerRules::get();
        $groupedData = $rankerRules->groupBy('uniq_id')->map(function ($group) {
            return $group->first();
        });

        $data['list'] = $groupedData;
        return view('admin.ranker_rule.list',$data);
    }


    public function add(){
        $data = [];
        $data['languages'] = Language::where('status',1)->get();
        return view('admin.ranker_rule.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'status' => 'required'
                        ]);

            $uniq_id = rand(1000, 9999);
            $status = $request->input('status', 1);

            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'rule_') === 0 && !empty($value)) {
                    $languageId = substr($key, 5);

                    if (Language::find($languageId)) {
                        RankerRules::create([
                            'uniq_id'  => $uniq_id,
                            'language' => $languageId,
                            'rule'     => $value,
                            'status'   => $status,
                        ]);
                    }
                }
            }

            toastr()->success('Ranker Rules added successfully.');
            return redirect()->route('admin.ranker_rule');
    }

    public function edit(Request $request){
        $ranker_rule = RankerRules::findOrFail($request->id);
        $ranker_rules = RankerRules::where('uniq_id', $ranker_rule->uniq_id)->get();

        $rankersByLanguage = $ranker_rules->keyBy('language');
        $languages = Language::all();
        return view('admin.ranker_rule.edit', compact('ranker_rule', 'languages', 'rankersByLanguage'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'uniq_id' => 'required'
        ]);

        $uniq_id = $request->uniq_id;
        $loginCheck = RankerRules::where('uniq_id', $uniq_id)->firstOrFail();

        if ($loginCheck) {
            $status = $request->input('status', 1);

            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'rule_') === 0 && !empty($value)) {
                    $languageId = substr($key, 5);

                    if (Language::find($languageId)) {
                        $rankerRule = RankerRules::where('uniq_id', $uniq_id)
                                                ->where('language', $languageId)
                                                ->first();

                        if ($rankerRule) {
                            $rankerRule->update([
                                'rule'   => $value,
                                'status' => $status,
                            ]);
                        } else {
                            RankerRules::create([
                                'uniq_id'  => $uniq_id,
                                'language' => $languageId,
                                'rule'     => $value,
                                'status'   => $status,
                            ]);
                        }
                    }
                }
            }

            toastr()->success('Ranker Rules updated successfully.');
            return redirect()->route('admin.ranker_rule');
        } else {
            return back()->withInput();
        }
    }


    public function delete(Request $request){
        $id = $request->id;
        RankerRules::where('uniq_id',$id)->delete();
        toastr()->success('Ranker Rule deleted successfully.');
        return redirect()->route('admin.ranker_rule');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = RankerRules::where('uniq_id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Ranker Rule status change successfully.');
        return redirect()->route('admin.ranker_rule');
    }
}
