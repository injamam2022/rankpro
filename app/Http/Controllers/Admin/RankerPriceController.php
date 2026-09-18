<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\Ranker_price;
use App\Models\Ranker;

class RankerPriceController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = Ranker_price::select(['ranker_prices.*','rankers.name as ranker_name'])
                        ->leftJoin('rankers', 'ranker_prices.ranker_id', '=', 'rankers.id')
                        ->get();
        return view('admin.ranker_price.list',$data);
    }


    public function add(){
        $data = [];
        $data['ranker_list'] = Ranker::where('status',1)->get();
        return view('admin.ranker_price.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'ranker_id' => 'required'
                        ]);

        $input = $request->all();

        // $loginCheck = Ranker_price::where('email',$request->email)->first();

        if (1){
            $insertData = [];
            $insertData['ranker_id'] = $request->ranker_id;
            $insertData['title'] = $request->title;
            $insertData['description'] = $request->description;
            $insertData['type'] = $request->type;
            $insertData['price'] = $request->price;
            $insertData['dis_price'] = $request->dis_price;
            $insertData['time'] = $request->time;
            $insertData['status'] = $request->status;

            Ranker_price::create($insertData);

            toastr()->success('Ranker price added successfully.');
            return redirect()->route('admin.ranker_price.add');
        }else{
            toastr()->warning('Ranker price already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = Ranker_price::where('id',$request->id)->first();
        $data['ranker_list'] = Ranker::where('status',1)->get();
        return view('admin.ranker_price.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'ranker_id' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = Ranker_price::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            $insertData['ranker_id'] = $request->ranker_id;
            $insertData['title'] = $request->title;
            $insertData['description'] = $request->description;
            $insertData['type'] = $request->type;
            $insertData['price'] = $request->price;
            $insertData['dis_price'] = $request->dis_price;
            $insertData['time'] = $request->time;

            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Ranker price updated successfully.');
            return redirect()->route('admin.ranker_price');
        }else{
            toastr()->warning('Ranker price already exist');
            return back()->withInput();
        }
    }

    public function delete(Request $request){
        $id = $request->id;
        Ranker_price::where('id',$id)->delete();
        toastr()->success('Ranker price deleted successfully.');
        return redirect()->route('admin.ranker_price');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = Ranker_price::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Ranker price status change successfully.');
        return redirect()->route('admin.ranker_price');
    }

}
