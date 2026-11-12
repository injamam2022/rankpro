<?php

namespace App\Support;

/**
 * Predicted NEET All-India Rank from a mock score.
 *
 * Same calculation as "RANK PREDICTOR DATA" Sheet6:
 *   r24 = rank at this score, or the next lower score, on the 2024 sheet
 *   r25 = the same lookup on the 2025 (MIN) sheet
 *   r26 = average(r24, r25) * (students 2025 / students 2026)
 *   students 2026 = 1.1 * students 2025 (2,209,318)
 *   predicted rank = max(1, round(median(r24, r25, r26)))
 * Min and max ranks are calculated separately, then the midpoint is their average.
 *
 * A full paper (600–720 marks) is looked up on its raw score.
 * A part-syllabus paper, or any aggregate whose max exceeds 720
 * (multi-test totals), is scaled to a 720-mark equivalent first.
 */
class NeetAirPredictor
{
    protected const NEET_TOTAL = 720;

    protected const FULL_PAPER_MARKS = 600;

    /** @var array<string, mixed>|null */
    protected static $table;

    public static function project($score, $max = self::NEET_TOTAL): array
    {
        $score = (float) $score;
        $max = (float) $max;
        // Raw NEET-style papers land in [600, 720]. Below that is part syllabus;
        // above 720 is almost always summed marks across exams — never treat that
        // raw total as a single NEET score (it would incorrectly predict AIR #1).
        $scaled = $max > 0 && ($max < self::FULL_PAPER_MARKS || $max > self::NEET_TOTAL);
        $equivalent = $scaled ? ($score * self::NEET_TOTAL / $max) : $score;

        $low = self::predict($equivalent, 1);
        $high = self::predict($equivalent, 2);
        if ($high < $low) {
            [$low, $high] = [$high, $low];
        }
        $mid = (int) round(($low + $high) / 2);
        $pool = self::candidatePool();
        $percentile = $pool > 0
            ? round((($pool - $mid + 1) / $pool) * 100, 2)
            : 0;

        return [
            'low' => $low,
            'high' => $high,
            'mid' => $mid,
            'percentile' => max(0, min(100, $percentile)),
            'equivalent' => $equivalent,
            'scaled' => $scaled,
        ];
    }

    public static function rangeLabel(array $projection): string
    {
        return 'AIR #'.number_format((int) $projection['low']).' – #'.number_format((int) $projection['high']);
    }

    protected static function predict(float $score, int $column): int
    {
        $table = self::table();
        $r24 = self::lookup($table['y2024'], $score, $column);
        $r25 = self::lookup($table['y2025'], $score, $column);

        if ($r24 !== null && $r25 !== null) {
            $growth = 1 / (float) $table['candidate_growth'];
            $r26 = (($r24 + $r25) / 2) * $growth;

            return max(1, (int) round(self::median($r24, $r25, $r26)));
        }

        $known = $r24 ?? $r25;
        if ($known !== null) {
            return max(1, (int) round($known));
        }

        $floor = $table['y2024'][0] ?? null;

        return max(1, (int) round((float) ($floor[$column] ?? 1)));
    }

    /**
     * Greatest published score that is still less than or equal to $score.
     * This matches Excel XLOOKUP match mode -1. Scores below the sheet return null.
     *
     * @param  array<int, array{0: float|int, 1: float|int, 2: float|int}>  $rows
     */
    protected static function lookup(array $rows, float $score, int $column): ?float
    {
        $count = count($rows);
        if ($count === 0 || $score < (float) $rows[0][0]) {
            return null;
        }

        $lo = 0;
        $hi = $count - 1;
        $found = null;
        while ($lo <= $hi) {
            $mid = intdiv($lo + $hi, 2);
            if ((float) $rows[$mid][0] <= $score) {
                $found = $mid;
                $lo = $mid + 1;
            } else {
                $hi = $mid - 1;
            }
        }

        return $found === null ? null : (float) $rows[$found][$column];
    }

    protected static function median(float $a, float $b, float $c): float
    {
        $values = [$a, $b, $c];
        sort($values);

        return $values[1];
    }

    protected static function candidatePool(): float
    {
        $table = self::table();

        return (float) $table['students_2025'] * (float) $table['candidate_growth'];
    }

    protected static function table(): array
    {
        if (self::$table !== null) {
            return self::$table;
        }

        $path = __DIR__.'/data/neet_air_predictor.json';
        $decoded = json_decode((string) file_get_contents($path), true);
        if (!is_array($decoded) || empty($decoded['y2024']) || empty($decoded['y2025'])) {
            throw new \RuntimeException('NEET rank predictor table is missing or unreadable.');
        }

        self::$table = $decoded;

        return self::$table;
    }
}
