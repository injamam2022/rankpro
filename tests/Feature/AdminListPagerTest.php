<?php

namespace Tests\Feature;

use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class AdminListPagerTest extends TestCase
{
    public function test_list_pager_does_not_repeat_showing_results_text()
    {
        $items = collect(range(1, 44))->map(fn ($i) => (object) ['id' => $i]);
        $list = new LengthAwarePaginator(
            $items->forPage(1, 10)->values(),
            44,
            10,
            1,
            ['path' => '/admin/question-paper']
        );

        $html = view('admin.include.list_pager', compact('list'))->render();

        $this->assertStringContainsString('Showing 1–10 of 44', $html);
        $this->assertStringNotContainsString('of 44 results', $html);
        $this->assertStringContainsString('page-item', $html);
    }
}
