<?php

namespace Tests\Unit;

use App\Support\NeetAirPredictor;
use PHPUnit\Framework\TestCase;

class NeetAirPredictorTest extends TestCase
{
    public function test_score_611_matches_the_spreadsheet_example(): void
    {
        $air = NeetAirPredictor::project(611, 720);

        $this->assertSame(29584, $air['low']);
        $this->assertSame(30090, $air['high']);
        $this->assertSame(29837, $air['mid']);
        $this->assertFalse($air['scaled']);
    }

    public function test_published_full_paper_scores(): void
    {
        $top = NeetAirPredictor::project(720, 720);
        $this->assertSame(1, $top['low']);
        $this->assertSame(1, $top['high']);

        $score545 = NeetAirPredictor::project(545, 720);
        $this->assertSame(71620, $score545['low']);
        $this->assertSame(72499, $score545['high']);

        $score345 = NeetAirPredictor::project(345, 720);
        $this->assertSame(372686, $score345['low']);
        $this->assertSame(375084, $score345['high']);
    }

    public function test_part_syllabus_score_is_scaled_to_720_before_lookup(): void
    {
        $part = NeetAirPredictor::project(100, 180);
        $full = NeetAirPredictor::project(400, 720);

        $this->assertTrue($part['scaled']);
        $this->assertEqualsWithDelta(400, $part['equivalent'], 0.001);
        $this->assertSame($full['low'], $part['low']);
        $this->assertSame($full['high'], $part['high']);
        $this->assertSame(264776, $part['low']);
        $this->assertSame(266702, $part['high']);
    }

    public function test_aggregated_multi_test_totals_are_scaled_not_treated_as_raw_neet(): void
    {
        // Answers Analytics sums marks across exams (e.g. 3678/8040). Without
        // scaling, 3678 would look up as an impossible NEET score → AIR #1.
        $aggregated = NeetAirPredictor::project(3678, 8040);
        $equivalent = NeetAirPredictor::project(329.373134328, 720);

        $this->assertTrue($aggregated['scaled']);
        $this->assertEqualsWithDelta(329.373134328, $aggregated['equivalent'], 0.001);
        $this->assertSame($equivalent['low'], $aggregated['low']);
        $this->assertSame($equivalent['high'], $aggregated['high']);
        $this->assertNotSame(1, $aggregated['low']);
        $this->assertNotSame(1, $aggregated['high']);
    }

    public function test_range_label(): void
    {
        $this->assertSame(
            'AIR #71,620 – #72,499',
            NeetAirPredictor::rangeLabel(NeetAirPredictor::project(545, 720))
        );
    }
}
