<?php

namespace App\Support;

class ReportProblemCatalog
{
    public static function faqs(): array
    {
        return [
            [
                'id' => 'faq-1',
                'category' => 'Mock Exam Simulation & CBT',
                'question' => 'How do I review questions marked for review before final submission?',
                'answer' => 'In the right-side question palette of the CBT simulator, questions marked for review appear in violet/purple. You can jump directly to any question by clicking its number, modify your answer or click "Mark for Review" again to toggle its status before clicking "Submit Exam".',
                'helpful' => 142,
            ],
            [
                'id' => 'faq-2',
                'category' => 'Scoring & Negative Marking',
                'question' => 'How is negative marking (-1) applied according to NTA guidelines?',
                'answer' => 'Every correct question earns +4 marks. Every incorrect response deducts 1 mark (-1). Unattempted questions incur 0 marks. In Section B (15 questions), you are only evaluated on the first 10 questions attempted; any subsequent attempts beyond 10 are ignored.',
                'helpful' => 219,
            ],
            [
                'id' => 'faq-3',
                'category' => 'OMR vs Online CBT',
                'question' => 'What is the difference between Online CBT and Offline OMR mock modes?',
                'answer' => 'Online CBT mode simulates digital test practice with instantaneous bubbling and timer alarms. Offline OMR mode simulates the physical NTA exam hall experience where you print or bubble the 200-question circular response sheet, training your manual bubbling speed (~12 mins cost).',
                'helpful' => 185,
            ],
            [
                'id' => 'faq-4',
                'category' => 'Rank Pro Leaderboard & Percentile',
                'question' => 'How is my All-India Percentile and Rank calculated on the Leaderboard?',
                'answer' => 'Percentiles are calculated against all verified candidates who completed the same test window across India. In case of identical scores, NTA tie-breaking rules are applied: higher Biology marks rank first, followed by Chemistry, then fewer negative responses.',
                'helpful' => 198,
            ],
            [
                'id' => 'faq-5',
                'category' => 'Technical & Browser Issues',
                'question' => 'What should I do if my browser freezes or crashes during an active mock?',
                'answer' => 'Your responses are automatically auto-saved in local cache every 10 seconds. Simply refresh your browser tab or restart your browser. When you return to the mock, your timer and previously bubbled responses will resume seamlessly.',
                'helpful' => 164,
            ],
            [
                'id' => 'faq-6',
                'category' => 'Previous Year Questions (PYQs)',
                'question' => 'How can I practice Previous Year Questions by specific year and subject?',
                'answer' => 'Navigate to "Exam Info & Syllabus" and open Previous Year Questions. You can filter by year and subject, use pagination, view NCERT-linked solutions, and practice authentic NTA questions.',
                'helpful' => 173,
            ],
        ];
    }

    public static function faqTopics(): array
    {
        return [
            'All',
            'Mock Exam Simulation & CBT',
            'Scoring & Negative Marking',
            'OMR vs Online CBT',
        ];
    }

    public static function categories(): array
    {
        return [
            'Mock Test Queries' => [
                'Timer / Clock Synchronization Issue',
                'Question Diagram / Image Not Displaying',
                'Answer Option Selection Not Registering',
                'Accidental or Premature Auto-Submission',
                'OMR Response Sheet Bubble Discrepancy',
            ],
            'Question & Solution Discrepancy' => [
                'Wrong Correct Option in Official Answer Key',
                'Ambiguous / Typo in Question Text',
                'NCERT Citation / Explanation Error',
                'Out of NMC/NTA NEET 2027 Syllabus Question',
            ],
            'Website & Technical Issues' => [
                'Page Freezing or Unusually Slow Loading',
                'Mobile / Tablet Layout Overlap',
                'Audio / Notification Alert Glitch',
                'Browser Local Storage Sync Error',
            ],
            'Scorecard & All-India Ranking' => [
                'Total Marks Calculation Discrepancy',
                'Negative Marking (-1) Penalty Over-Deducted',
                'Rank Pro Leaderboard AIR Position Missing',
                'Percentile Mismatch with Score',
            ],
            'Account & Profile Issues' => [
                'Target Score Not Updating in Dashboard',
                'Profile Avatar / Image Upload Error',
                'Password Reset / Login Credential Trouble',
                'Roll ID / Registration Information Discrepancy',
            ],
        ];
    }

    public static function encodeMessage(array $meta, string $body): string
    {
        return "===RP_META===\n".json_encode($meta, JSON_UNESCAPED_UNICODE)."\n===RP_BODY===\n".$body;
    }

    public static function decodeMessage(?string $message): array
    {
        $message = (string) $message;
        $meta = [];
        $body = $message;
        if (preg_match('/===RP_META===\s*(\{.*?\})\s*===RP_BODY===\s*(.*)/s', $message, $m)) {
            $decoded = json_decode($m[1], true);
            if (is_array($decoded)) {
                $meta = $decoded;
            }
            $body = trim($m[2]);
        }

        return [$meta, $body];
    }

    public static function statusLabel($status): string
    {
        $status = (int) $status;
        if ($status === 1) {
            return 'Resolved';
        }
        if ($status === 2) {
            return 'In Review';
        }

        return 'Open';
    }

    public static function ticketNumber($id): string
    {
        return 'TKT-NEET-'.str_pad((string) $id, 4, '0', STR_PAD_LEFT);
    }
}
