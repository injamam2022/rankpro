<?php

namespace Database\Seeders;

use App\Models\CoursePackage;
use App\Models\CoursePackageFeature;
use App\Models\CoursePackageMode;
use App\Models\CourseSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CoursePackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'code' => 'free_custom',
                'name' => 'Free Custom Test Builder',
                'slug' => 'free-custom-test-builder',
                'tagline' => 'Free Custom Test segment — non-premium question bank',
                'theme' => 'sky',
                'display_price' => 0,
                'is_free' => true,
                'includes_online' => true,
                'includes_omr' => false,
                'mode_selection' => 'fixed',
                'schedule_scope' => 'none',
                'sort_order' => 1,
                'features' => [
                    'Uses the existing Custom Test module (non-premium questions)',
                    'Subject, chapter, topic, and difficulty selection',
                    '45 practice questions',
                    'Instant results and answer key',
                    'Kota faculty curated questions with detailed solutions',
                    'NCERT syllabus based questions',
                    'Score 40+ correct answers to unlock one additional free custom test (first test only)',
                ],
                'modes' => [
                    [
                        'mode_code' => 'online',
                        'label' => 'Online (Custom Test — non-premium)',
                        'price' => 0,
                        'requires_location' => false,
                        'requires_address' => false,
                        'includes_online' => true,
                        'includes_omr' => false,
                        'help_text' => 'Opens the existing Custom Test builder. Does not use premium questions.',
                        'sort_order' => 1,
                    ],
                ],
            ],
            [
                'code' => 'chapter_master',
                'name' => 'Rank Chapter Master',
                'slug' => 'rank-chapter-master',
                'tagline' => 'Premium chapter-wise tests across PCB',
                'theme' => 'pink',
                'display_price' => 49,
                'is_free' => false,
                'includes_online' => true,
                'includes_omr' => false,
                'mode_selection' => 'fixed',
                'schedule_scope' => 'chapter',
                'sort_order' => 2,
                'features' => [
                    'Premium question bank only (not the free Custom Test bank)',
                    '85 chapters across PCB',
                    '45 questions per chapter',
                    'Separate Botany and Zoology tests',
                    'Detailed performance review',
                    'Topic-level strengths and weaknesses analysis',
                    'NCERT syllabus based questions',
                    'Kota faculty curated questions with detailed solutions',
                ],
                'modes' => [
                    [
                        'mode_code' => 'online',
                        'label' => 'Online (Premium Chapter Master)',
                        'price' => 49,
                        'requires_location' => false,
                        'requires_address' => false,
                        'includes_online' => true,
                        'includes_omr' => false,
                        'help_text' => 'Pick subject → chapter/topic. Premium questions only; empty topics show Coming soon.',
                        'sort_order' => 1,
                    ],
                ],
            ],
            [
                'code' => 'rank_builder',
                'name' => 'Rank Builder',
                'slug' => 'rank-builder',
                'tagline' => '18 structured tests with Online and OMR included',
                'theme' => 'blue',
                'display_price' => 2499,
                'is_free' => false,
                'includes_online' => true,
                'includes_omr' => true,
                'mode_selection' => 'fixed',
                'schedule_scope' => 'part',
                'sort_order' => 3,
                'features' => [
                    '18 tests: 12 unit tests, 4 review tests, and 2 semi-major tests',
                    'Online and OMR testing',
                    'Subject, chapter, and topic analysis',
                    '8 additional custom tests',
                    'Individual and combined progress reports',
                    'Detailed performance review',
                    'Mistake Monitor to identify recurring errors',
                    'Strength and weaknesses analysis',
                    'NCERT syllabus based questions',
                    'Kota faculty curated questions with detailed solutions',
                ],
                'modes' => [
                    [
                        'mode_code' => 'bundle',
                        'label' => 'Online + OMR (both included)',
                        'price' => 2499,
                        'requires_location' => false,
                        'requires_address' => false,
                        'includes_online' => true,
                        'includes_omr' => true,
                        'help_text' => 'One purchase unlocks both online CBT and OMR access. No mode choice needed.',
                        'sort_order' => 1,
                    ],
                ],
            ],
            [
                'code' => 'subject_pro',
                'name' => 'Subject Pro',
                'slug' => 'subject-pro',
                'tagline' => 'Offline series with physical papers at home',
                'theme' => 'orange',
                'display_price' => 2999,
                'is_free' => false,
                'includes_online' => false,
                'includes_omr' => true,
                'mode_selection' => 'fixed',
                'schedule_scope' => 'part',
                'sort_order' => 4,
                'features' => [
                    'Offline Test Series',
                    'Subject-wise test practice',
                    'Combined Botany + Zoology tests',
                    '1:1 faculty doubt-solving',
                    'Physical test papers delivered to your home',
                    'Mistake Monitor to identify recurring errors',
                    'Chapter-wise performance analysis',
                    'Progress report',
                    'Strength and weaknesses analysis',
                    'NCERT syllabus based questions',
                    'Kota faculty curated questions with detailed solutions',
                ],
                'modes' => [
                    [
                        'mode_code' => 'delivery',
                        'label' => 'Home Delivery (Offline papers)',
                        'price' => 2999,
                        'requires_location' => false,
                        'requires_address' => true,
                        'includes_online' => false,
                        'includes_omr' => true,
                        'help_text' => 'Physical OMR papers are shipped to your address.',
                        'sort_order' => 1,
                    ],
                ],
            ],
            [
                'code' => 'rank_champion',
                'name' => 'Rank Champion',
                'slug' => 'rank-champion',
                'tagline' => '12 full-syllabus mocks with Online and OMR included',
                'theme' => 'violet',
                'display_price' => 3499,
                'is_free' => false,
                'includes_online' => true,
                'includes_omr' => true,
                'mode_selection' => 'fixed',
                'schedule_scope' => 'full',
                'sort_order' => 5,
                'features' => [
                    '12 full-syllabus tests',
                    'Online and OMR testing',
                    'Strength and weakness analysis',
                    '15 additional custom tests',
                    'Individual and combined progress reports',
                    'Detailed accuracy review',
                    '1:1 faculty doubt-solving',
                    'Mistake Monitor',
                    'NCERT syllabus based questions',
                    'Kota faculty curated questions with detailed solutions',
                ],
                'modes' => [
                    [
                        'mode_code' => 'bundle',
                        'label' => 'Online + OMR (both included)',
                        'price' => 3499,
                        'requires_location' => false,
                        'requires_address' => false,
                        'includes_online' => true,
                        'includes_omr' => true,
                        'help_text' => 'One purchase unlocks both online CBT and OMR access.',
                        'sort_order' => 1,
                    ],
                ],
            ],
            [
                'code' => 'omr_master',
                'name' => 'OMR Master',
                'slug' => 'omr-master',
                'tagline' => '30 physical OMR tests — Centre or Home delivery',
                'theme' => 'green',
                'display_price' => 4999,
                'is_free' => false,
                'includes_online' => false,
                'includes_omr' => true,
                'mode_selection' => 'choose_one',
                'schedule_scope' => 'both',
                'sort_order' => 6,
                'features' => [
                    '30 physical OMR tests',
                    '18 part-syllabus and 12 full-syllabus tests',
                    'NCERT syllabus based questions',
                    'Kota faculty curated questions with detailed solutions',
                    'Performance analysis and Mistake Monitor',
                    '15 additional custom tests',
                    'Weekly and monthly reviews',
                    'Strength and weaknesses analysis',
                    '1:1 faculty guidance and study planning',
                    'Centre-based exams or physical papers delivered home',
                ],
                'modes' => [
                    [
                        'mode_code' => 'centre',
                        'label' => 'Centre (come for exam)',
                        'price' => 4999,
                        'requires_location' => true,
                        'requires_address' => false,
                        'includes_online' => false,
                        'includes_omr' => true,
                        'help_text' => 'Appear at a RankPro centre for OMR exams.',
                        'sort_order' => 1,
                    ],
                    [
                        'mode_code' => 'delivery',
                        'label' => 'Home Delivery',
                        'price' => 5999,
                        'requires_location' => false,
                        'requires_address' => true,
                        'includes_online' => false,
                        'includes_omr' => true,
                        'help_text' => 'Physical OMR papers delivered to your home.',
                        'sort_order' => 2,
                    ],
                ],
            ],
            [
                'code' => 'mission_720',
                'name' => 'Rank Pro Mission 720',
                'slug' => 'rank-pro-mission-720',
                'tagline' => 'Complete mission with weekly centre tests & mentorship',
                'theme' => 'blue',
                'display_price' => 6999,
                'is_free' => false,
                'includes_online' => true,
                'includes_omr' => true,
                'mode_selection' => 'fixed',
                'schedule_scope' => 'both',
                'sort_order' => 7,
                'features' => [
                    '85 chapter-wise tests across PCB',
                    '18 part-syllabus tests',
                    '12 full-syllabus mock tests',
                    'Weekly centre-based tests',
                    'NEET-like exam environment',
                    'NEET Ranker 1:1 mentorship',
                    '1:1 faculty guidance and doubt-solving',
                    'Advanced performance and error analytics',
                    'Question-level insights and progress reports',
                    'Personalised study planning',
                    'NCERT syllabus based questions',
                    'Kota faculty curated questions with detailed solutions',
                ],
                'modes' => [
                    [
                        'mode_code' => 'centre',
                        'label' => 'Centre-based mission',
                        'price' => 6999,
                        'requires_location' => true,
                        'requires_address' => false,
                        'includes_online' => true,
                        'includes_omr' => true,
                        'help_text' => 'Weekly centre tests plus full analytics and mentorship.',
                        'sort_order' => 1,
                    ],
                ],
            ],
        ];

        foreach ($packages as $data) {
            $features = $data['features'];
            $modes = $data['modes'];
            unset($data['features'], $data['modes']);

            $package = CoursePackage::updateOrCreate(
                ['code' => $data['code']],
                array_merge($data, ['status' => 1])
            );

            $package->features()->delete();
            foreach ($features as $i => $text) {
                CoursePackageFeature::create([
                    'course_package_id' => $package->id,
                    'text' => $text,
                    'sort_order' => $i + 1,
                ]);
            }

            $package->modes()->delete();
            foreach ($modes as $mode) {
                CoursePackageMode::create(array_merge($mode, [
                    'course_package_id' => $package->id,
                    'status' => 1,
                ]));
            }
        }

        $this->seedSchedule();
    }

    private function seedSchedule(): void
    {
        $path = database_path('seeders/course_schedule_data.json');
        if (!File::exists($path)) {
            return;
        }

        $rows = json_decode(File::get($path), true) ?: [];
        if (empty($rows)) {
            return;
        }

        CourseSchedule::query()->delete();

        foreach ($rows as $row) {
            CourseSchedule::create([
                'section' => $row['section'] ?? 'part',
                'seq' => (int) ($row['seq'] ?? 0),
                'test_date' => $row['test_date'] ?? null,
                'test_type' => $row['test_type'] ?? null,
                'timing' => $row['timing'] ?? null,
                'subjects' => $row['subjects'] ?? [],
                'status' => 1,
            ]);
        }
    }
}
