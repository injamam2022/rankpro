<?php

namespace Tests\Unit;

use App\Models\Question_detail;
use PHPUnit\Framework\TestCase;

class QuestionContentHashTest extends TestCase
{
    public function test_same_wording_with_different_html_matches()
    {
        $formatted = Question_detail::contentHash('<p>What is a tonoplast?</p>', 'Outer membrane', 'B', 'C', 'D', 0, 0, 0, 0);
        $plain = Question_detail::contentHash('What is a tonoplast?', 'Outer&nbsp;membrane', 'B', 'C', 'D', 0, 0, 0, 0);

        $this->assertNotNull($formatted);
        $this->assertSame($formatted, $plain);
    }

    public function test_different_options_do_not_match()
    {
        $first = Question_detail::contentHash('Which is incorrect?', 'A', 'B', 'C', 'D', 0, 0, 0, 0);
        $second = Question_detail::contentHash('Which is incorrect?', 'W', 'X', 'Y', 'Z', 0, 0, 0, 0);

        $this->assertNotSame($first, $second);
    }

    public function test_empty_content_has_no_hash()
    {
        $this->assertNull(Question_detail::contentHash('   ', '', '', '', '', 0, 0, 0, 0));
    }
}
