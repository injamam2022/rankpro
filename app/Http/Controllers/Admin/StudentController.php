<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\User;
use App\Models\Exam_user;
use App\Models\Exam_result;
use App\Models\Question_detail;
use App\Models\Question_paper_question;
use App\Models\Offline_exam_question;

use DB;

class StudentController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = User::get();
        // dd($data['list']);
        return view('admin.student.list',$data);
    }


    public function add(){
        $data = [];
        return view('admin.student.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = User::where('email',$request->email)->first();

        if (!$loginCheck){
            $insertData = [];
            if ($image = $request->file('profile_img')){
                $insertData['profile_img'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/profileImage/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$insertData['profile_img']);


                $destinationPath = public_path('/uploads/profileImage');
                $image->move($destinationPath, $insertData['profile_img']);
            }

            do {
                $rankproId = random_int(100000, 999999);
            } while (User::where('rankpro_id', $rankproId)->exists());

            $insertData['first_name'] = $request->first_name;
            $insertData['last_name'] = $request->last_name;
            $insertData['address'] = $request->address;
            $insertData['mobile_number'] = $request->mobile_number;
            $insertData['is_whatsapp'] = $request->is_whatsapp;
            $insertData['email_id'] = $request->email_id;
            $insertData['father_full_name'] = $request->father_full_name;
            $insertData['father_occupation'] = $request->father_occupation;
            $insertData['father_mobile_number'] = $request->father_mobile_number;
            $insertData['father_qualification'] = $request->father_qualification;
            $insertData['mother_full_name'] = $request->mother_full_name;
            $insertData['mother_occupation'] = $request->mother_occupation;
            $insertData['mother_mobile_number'] = $request->mother_mobile_number;
            $insertData['mother_qualification'] = $request->mother_qualification;
            $insertData['qualification_details'] = $request->qualification_details;
            $insertData['access_token'] = md5(time().$request->email);
            $insertData['hash_code'] = md5(time().$request->email);
            $insertData['password'] = md5($request->password);
            $insertData['status'] = $request->status;
            $insertData['rankpro_id'] = $rankproId;

            $user = User::create($insertData);


            toastr()->success('Student added successfully.');
            return redirect()->route('admin.student.add');
        }else{
            toastr()->warning('Student already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = User::where('id',$request->id)->first();
        return view('admin.student.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'first_name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = User::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            if ($image = $request->file('profile_img')){
                $insertData['profile_img'] = time().'.'.$image->getClientOriginalExtension();


                $destinationPath = public_path('/uploads/profileImage');
                $image->move($destinationPath, $insertData['profile_img']);

                if($loginCheck->profile_img){
                    if (file_exists(public_path($loginCheck->profile_img))) {
                        unlink(public_path($loginCheck->profile_img));
                    }
                }
                $insertData['profile_img'] = $insertData['profile_img'];
            }
            if ($image = $request->file('certificate')){
                $insertData['certificate'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/certificate');
                $image->move($destinationPath, $insertData['certificate']);

                if($loginCheck->certificate){
                    if (file_exists(public_path($loginCheck->certificate))) {
                        unlink(public_path($loginCheck->certificate));
                    }
                }
                $insertData['certificate'] = 'uploads/certificate/'.$insertData['certificate'];
            }
            if ($image = $request->file('guardian_signature')){
                $insertData['guardian_signature'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/guardian_signature');
                $image->move($destinationPath, $insertData['guardian_signature']);

                if($loginCheck->guardian_signature){
                    if (file_exists(public_path($loginCheck->guardian_signature))) {
                        unlink(public_path($loginCheck->guardian_signature));
                    }
                }
                $insertData['guardian_signature'] = 'uploads/guardian_signature/'.$insertData['guardian_signature'];
            }
            if ($image = $request->file('student_signature')){
                $insertData['student_signature'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/student_signature');
                $image->move($destinationPath, $insertData['student_signature']);

                if($loginCheck->student_signature){
                    if (file_exists(public_path($loginCheck->student_signature))) {
                        unlink(public_path($loginCheck->student_signature));
                    }
                }
                $insertData['student_signature'] = 'uploads/student_signature/'.$insertData['student_signature'];
            }

            $insertData['first_name'] = $request->first_name;
            $insertData['last_name'] = $request->last_name;
            $insertData['address'] = $request->address;
            $insertData['mobile_number'] = $request->mobile_number;
            $insertData['is_whatsapp'] = ($request->is_whatsapp)?1:0;
            $insertData['email_id'] = $request->email_id;
            $insertData['father_full_name'] = $request->father_full_name;
            $insertData['father_occupation'] = $request->father_occupation;
            $insertData['father_mobile_number'] = $request->father_mobile_number;
            $insertData['father_qualification'] = $request->father_qualification;
            $insertData['mother_full_name'] = $request->mother_full_name;
            $insertData['mother_occupation'] = $request->mother_occupation;
            $insertData['mother_mobile_number'] = $request->mother_mobile_number;
            $insertData['mother_qualification'] = $request->mother_qualification;
            $insertData['qualification_details'] = $request->qualification_details;

            $insertData['address'] = $request->address;
            if($request->password){
                $insertData['password'] = md5($request->password);
            }

            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Student updated successfully.');
            return redirect()->route('admin.student');
        }else{
            toastr()->warning('Student already exist');
            return back()->withInput();
        }
    }

    public function delete(Request $request){
        $id = $request->id;
        User::where('id',$id)->delete();
        toastr()->success('Student deleted successfully.');
        return redirect()->route('admin.student');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = User::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Student status change successfully.');
        return redirect()->route('admin.student');
    }

    public function exam_list(Request $request)
    {
        $user = User::findOrFail($request->id);
        $userId = (int) $user->id;

        $list = Exam_user::select([
                'exam_users.*',
                'exams.name',
                'exams.exam_code',
                'exams.type',
            ])
            ->leftJoin('exams', 'exam_users.exam_id', '=', 'exams.id')
            ->where('exam_users.user_id', $userId)
            ->where('exams.is_deleted', 0)
            ->orderByDesc('exam_users.id')
            ->get();

        $rankRow = DB::select("
            SELECT COUNT(*) + 1 AS rank
            FROM (
                SELECT exam_users.user_id, SUM(exam_users.total_number) as total_number
                FROM exam_users
                LEFT JOIN exams ON exams.id = exam_users.exam_id
                WHERE exams.is_deleted = 0
                GROUP BY exam_users.user_id
            ) as totals
            WHERE total_number > (
                SELECT COALESCE(SUM(exam_users.total_number), 0)
                FROM exam_users
                LEFT JOIN exams ON exams.id = exam_users.exam_id
                WHERE exams.is_deleted = 0
                AND exam_users.user_id = ?
            )
        ", [$userId]);

        $youOverall = $this->studentResultStats($userId, null);
        $topperOverall = $this->topperResultStats(null);
        $averageOverall = $this->averageResultStats(null);

        return view('admin.student.exam_list', [
            'user' => $user,
            'list' => $list,
            'easy_toper_list' => $this->topperResultStats(1),
            'avg_easy_toper_list' => $this->averageResultStats(1),
            'my_easy_toper_list' => $this->studentResultStats($userId, 1),
            'medium_toper_list' => $this->topperResultStats(2),
            'avg_medium_toper_list' => $this->averageResultStats(2),
            'my_medium_toper_list' => $this->studentResultStats($userId, 2),
            'hard_toper_list' => $this->topperResultStats(3),
            'avg_hard_toper_list' => $this->averageResultStats(3),
            'my_hard_toper_list' => $this->studentResultStats($userId, 3),
            'topper_data' => $topperOverall,
            'average_data' => $averageOverall,
            'you_data' => $youOverall,
            'total_exam_attemped' => $list->count(),
            'total_question_attemped' => Exam_result::where('user_id', $userId)->where('answer', '!=', '')->count(),
            'your_score' => $rankRow[0]->rank ?? 1,
            'chart' => [
                'topper' => $this->scorePercent($topperOverall),
                'average' => $this->scorePercent($averageOverall),
                'you' => $this->scorePercent($youOverall),
                'your_mark' => (float) ($youOverall->total_mark ?? 0),
                'total_mark' => (float) ($youOverall->totals_marks_for_exam ?? 0),
            ],
        ]);
    }

    public function exam_details(Request $request)
    {
        $examUser = Exam_user::select([
                'exams.*',
                'exam_users.id as user_exam_id',
                'exam_users.user_id',
                'exam_users.exam_id as attempt_exam_id',
                'exam_users.total_answer',
                'exam_users.total_right_answer',
                'exam_users.total_number',
                'exam_users.percentage',
                'exam_users.rank',
            ])
            ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
            ->where('exam_users.id', $request->id)
            ->firstOrFail();

        $userId = (int) ($request->user_id ?: $examUser->user_id);
        if ((int) $examUser->user_id !== $userId) {
            abort(404);
        }

        $languageId = function_exists('getDefaultLanguage') ? (int) getDefaultLanguage() : 1;

        if ($examUser->question_paper_id) {
            $questions = Question_paper_question::select([
                    'question_paper_questions.id',
                    'question_paper_questions.question_id',
                    'question_paper_questions.question_number',
                    'questions.answer',
                    'questions.solution_video_link as video_link',
                ])
                ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                ->where('question_paper_id', $examUser->question_paper_id)
                ->orderBy('question_paper_questions.question_number')
                ->orderBy('question_paper_questions.id')
                ->get();

            foreach ($questions as $row) {
                $detail = Question_detail::where('language_id', $languageId)
                    ->where('question_id', $row->question_id)
                    ->first();
                $row->question_text = $detail->question_text ?? '';
                $result = Exam_result::where('exam_user_id', $examUser->user_exam_id)
                    ->where('exam_question_id', $row->id)
                    ->first();
                $row->student_answer = $result->answer ?? '';
                $row->correct_answer = $row->answer;
            }
        } else {
            $questions = Offline_exam_question::where('exam_id', $examUser->id)
                ->orderBy('question_number')
                ->orderBy('id')
                ->get();

            foreach ($questions as $row) {
                $result = Exam_result::where('exam_user_id', $examUser->user_exam_id)
                    ->where('exam_question_id', $row->id)
                    ->first();
                $row->student_answer = $result->answer ?? '';
                $row->correct_answer = $row->answer;
                $row->video_link = $row->video_link ?? '';
            }
        }

        return view('admin.student.exam_details', [
            'user' => User::find($userId),
            'attempt' => $examUser,
            'list' => $questions,
        ]);
    }

    private function resultBaseQuery($difficulty = null)
    {
        return Exam_result::query()
            ->leftJoin('exam_users', 'exam_users.id', '=', 'exam_results.exam_user_id')
            ->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
            ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
            ->leftJoin('question_papers', 'question_papers.id', '=', 'question_paper_questions.question_paper_id')
            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
            ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
            ->where('exams.is_deleted', 0)
            ->when($difficulty !== null, function ($query) use ($difficulty) {
                $query->whereRaw('COALESCE(questions.difficulty_level, offline_exam_questions.difficulty_level) = ?', [$difficulty]);
            });
    }

    private function topperResultStats($difficulty = null)
    {
        $row = $this->resultBaseQuery($difficulty)
            ->select([
                DB::raw('count(*) as total_question'),
                DB::raw('COUNT(CASE WHEN (exam_results.result != 0 AND exam_results.result != -1) THEN 1 END) as right_answer'),
                DB::raw('COUNT(CASE WHEN (exam_results.result = 0 AND exam_results.answer = 0) THEN 1 END) as skip_answer'),
                DB::raw('COUNT(CASE WHEN ((exam_results.result = 0 OR exam_results.result = -1) AND exam_results.answer != 0) THEN 1 END) as wrong_answer'),
                DB::raw('SUM(exam_results.result) as total_mark'),
                'exam_results.user_id',
            ])
            ->groupBy('exam_results.user_id')
            ->orderByDesc('total_mark')
            ->first();

        if ($row) {
            $row->totals_marks_for_exam = Exam_user::query()
                ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
                ->where('exam_users.user_id', $row->user_id)
                ->where('exams.is_deleted', 0)
                ->sum('exam_users.total_mark');
        }

        return $row;
    }

    private function averageResultStats($difficulty = null)
    {
        $sub = $this->resultBaseQuery($difficulty)
            ->select([
                DB::raw('count(*) as total_question'),
                DB::raw('COUNT(CASE WHEN (exam_results.result != 0 AND exam_results.result != -1) THEN 1 END) as right_answer'),
                DB::raw('COUNT(CASE WHEN (exam_results.result = 0 AND exam_results.answer = 0) THEN 1 END) as skip_answer'),
                DB::raw('COUNT(CASE WHEN ((exam_results.result = 0 OR exam_results.result = -1) AND exam_results.answer != 0) THEN 1 END) as wrong_answer'),
                DB::raw('SUM(exam_results.result) as total_mark'),
                DB::raw('COALESCE(exams.totals_marks_for_exam, question_papers.totals_marks_for_exam) as totals_marks_for_exam'),
            ])
            ->groupBy('exam_results.user_id');

        return DB::query()
            ->fromSub($sub, 'user_stats')
            ->select([
                DB::raw('AVG(total_question) as total_question'),
                DB::raw('AVG(right_answer) as right_answer'),
                DB::raw('AVG(skip_answer) as skip_answer'),
                DB::raw('AVG(wrong_answer) as wrong_answer'),
                DB::raw('AVG(totals_marks_for_exam) as totals_marks_for_exam'),
                DB::raw('AVG(total_mark) as total_mark'),
            ])
            ->first();
    }

    private function studentResultStats($userId, $difficulty = null)
    {
        $row = $this->resultBaseQuery($difficulty)
            ->select([
                DB::raw('count(*) as total_question'),
                DB::raw('COUNT(CASE WHEN (exam_results.result != 0 AND exam_results.result != -1) THEN 1 END) as right_answer'),
                DB::raw('COUNT(CASE WHEN (exam_results.result = 0 AND exam_results.answer = 0) THEN 1 END) as skip_answer'),
                DB::raw('COUNT(CASE WHEN ((exam_results.result = 0 OR exam_results.result = -1) AND exam_results.answer != 0) THEN 1 END) as wrong_answer'),
                DB::raw('SUM(exam_results.result) as total_mark'),
            ])
            ->where('exam_results.user_id', $userId)
            ->groupBy('exam_results.user_id')
            ->first();

        if ($row) {
            $row->totals_marks_for_exam = Exam_user::query()
                ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
                ->where('exam_users.user_id', $userId)
                ->where('exams.is_deleted', 0)
                ->sum('exam_users.total_mark');
        }

        return $row;
    }

    private function scorePercent($row): int
    {
        $mark = (float) ($row->total_mark ?? 0);
        $total = (float) ($row->totals_marks_for_exam ?? 0);
        if ($total <= 0) {
            return 0;
        }

        return (int) round(($mark / $total) * 100);
    }

}
