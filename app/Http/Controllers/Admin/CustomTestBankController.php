<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomTest;
use Illuminate\Http\Request;

class CustomTestBankController extends Controller
{
    public function list(Request $request)
    {
        $list = CustomTest::query()
            ->with(['user', 'subject'])
            ->whereNotNull('question_paper_id')
            ->orderByDesc('id')
            ->get();

        return view('admin.custom_test_bank.list', [
            'list' => $list,
        ]);
    }
}
