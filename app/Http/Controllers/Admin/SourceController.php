<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Source;
use App\Models\Subject;
use App\Support\AdminListQuery;
use Illuminate\Support\Facades\DB;

class SourceController extends Controller
{
    public function list(Request $request){
        $data = $this->sourceListData($request, true);
        return view('admin.source.list', $data);
    }

    public function printList(Request $request)
    {
        $data = $this->sourceListData($request, false);
        return view('admin.source.print', $data);
    }


    public function add(){
        $data = [];
        $data['subject_list'] = Subject::where('status','1')->where('is_deleted','0')->get();
        return view('admin.source.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required',
                            'subject_id' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = Source::where('name',$request->name)->first();

        if (!$loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['subject_id'] = $request->subject_id;
            $insertData['status'] = $request->status;

            Source::create($insertData);
        
            toastr()->success('Source added successfully.');
            return redirect()->route('admin.source');
        }else{
            toastr()->warning('Source already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Source::where('id',$request->id)->first();
        $data['subject_list'] = Subject::where('status','1')->where('is_deleted','0')->get();
        return view('admin.source.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required',
                            'subject_id' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Source::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            $insertData['name'] = $request->name;
            $insertData['subject_id'] = $request->subject_id;
            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Source updated successfully.');
            return redirect()->route('admin.source');
        }else{
            toastr()->warning('Source already exist');
            return back()->withInput();
        }
    }
    
    public function delete(Request $request){
        $id = $request->id;
        Source::where('id',$id)->delete();
        toastr()->success('Source deleted successfully.');
        return redirect()->route('admin.source');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Source::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Source status change successfully.');
        return redirect()->route('admin.source');
    }

    private function sourceListData(Request $request, bool $paginate): array
    {
        $data = [];
        $data['q'] = AdminListQuery::q($request);
        $data['per_page'] = AdminListQuery::perPage($request);
        $query = Source::select(['sources.*', 'subjects.name as subject_name'])
            ->leftJoin('subjects', 'sources.subject_id', '=', 'subjects.id');
        $tax = AdminListQuery::taxonomyOptions($request, ['subject']);
        $data = array_merge($data, $tax);
        if ($tax['subject_id']) {
            $query->where('sources.subject_id', $tax['subject_id']);
        }
        AdminListQuery::applySearch($query, $data['q'], ['sources.name', 'subjects.name']);

        $counts = $this->questionCountsForSources((clone $query)->pluck('sources.id'));
        $query->orderByDesc('sources.id');

        if ($paginate) {
            $list = $query->paginate($data['per_page'])->withQueryString();
            $rows = $list->getCollection();
        } else {
            $list = $query->get();
            $rows = $list;
        }

        $rows->each(function ($row) use ($counts) {
            $row->questions_count = (int) ($counts[(int) $row->id] ?? 0);
        });

        $data['list'] = $list;
        $data['questions_total'] = (int) $counts->sum();

        return $data;
    }

    private function questionCountsForSources($sourceIds)
    {
        $ids = collect($sourceIds)->map(fn ($id) => (int) $id)->filter()->unique()->values();
        if ($ids->isEmpty()) {
            return collect();
        }

        $query = DB::table('questions')
            ->selectRaw('source_id, COUNT(*) as total')
            ->where('is_deleted', 0)
            ->whereIn('source_id', $ids);
        if (session()->get('admmin_is_super') == 'T') {
            $subjectId = (int) session()->get('admmin_subject_id');
            if ($subjectId > 0) {
                $query->where('subject_id', $subjectId);
            }
        }

        return $query
            ->groupBy('source_id')
            ->pluck('total', 'source_id')
            ->mapWithKeys(fn ($total, $id) => [(int) $id => (int) $total]);
    }
    
}
