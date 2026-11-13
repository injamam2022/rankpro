<?php

namespace App\Support;

use App\Models\Chapter;
use App\Models\CoursePackage;
use App\Models\CoursePackageMode;
use App\Models\CoursePackageOrder;
use App\Models\CoursePackageOrderChapter;
use App\Models\CourseSchedule;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Question banks are split into two segments:
 * - Custom Test (existing free builder) → non-premium questions only
 * - Course Packages (Chapter Master, Rank Builder, etc.) → premium questions only
 */
class CoursePackageAccess
{
    /** Free Custom Test Builder — does NOT use the premium bank. */
    public const FREE_CUSTOM_CODE = 'free_custom';

    /** Rank Chapter Master: how many chapters one checkout can include. */
    public const CHAPTER_PICK_LIMIT = 6;

    public static function hasPackage(string $code, ?int $userId = null): bool
    {
        $userId = $userId ?: Auth::id();
        if (!$userId) {
            return false;
        }

        return CoursePackageOrder::query()
            ->where('user_id', $userId)
            ->whereIn('payment_status', ['SUCCESS', 'FREE'])
            ->whereHas('package', fn ($q) => $q->where('code', $code)->where('status', 1))
            ->exists();
    }

    public static function hasChapterMaster(?int $userId = null): bool
    {
        return self::chapterMasterFullAccess($userId) || count(self::ownedChapterIds($userId)) > 0;
    }

    /**
     * Entire Chapter Master (every PCB chapter):
     * Rank Pro Mission 720, or an admin grant of Chapter Master.
     * A ₹49 Chapter Master order only unlocks the chapters on that order.
     */
    public static function chapterMasterFullAccess(?int $userId = null): bool
    {
        $userId = $userId ?: Auth::id();
        if (!$userId) {
            return false;
        }

        if (self::hasPackage('mission_720', $userId)) {
            return true;
        }

        return self::adminGrantedFullChapterMaster($userId);
    }

    /** @deprecated Use chapterMasterFullAccess() */
    public static function chapterMasterLegacyFull(?int $userId = null): bool
    {
        return self::chapterMasterFullAccess($userId);
    }

    public static function adminGrantedFullChapterMaster(?int $userId = null): bool
    {
        $userId = $userId ?: Auth::id();
        if (!$userId) {
            return false;
        }

        return CoursePackageOrder::query()
            ->where('user_id', $userId)
            ->whereIn('payment_status', ['SUCCESS', 'FREE'])
            ->whereHas('package', fn ($q) => $q->where('code', 'chapter_master')->where('status', 1))
            ->whereDoesntHave('orderChapters')
            ->where(function ($q) {
                $q->where('payment_status', 'FREE')
                    ->orWhere('transaction_id', 'ADMIN-GRANT')
                    ->orWhere('final_amount', '<=', 0);
            })
            ->exists();
    }

    /** Paid Chapter Master order that never stored the chapters they picked. */
    public static function pendingChapterMasterOrder(?int $userId = null): ?CoursePackageOrder
    {
        $userId = $userId ?: Auth::id();
        if (!$userId || self::chapterMasterFullAccess($userId)) {
            return null;
        }

        return CoursePackageOrder::query()
            ->with('package')
            ->where('user_id', $userId)
            ->whereIn('payment_status', ['SUCCESS', 'FREE'])
            ->whereHas('package', fn ($q) => $q->where('code', 'chapter_master')->where('status', 1))
            ->whereDoesntHave('orderChapters')
            ->latest('id')
            ->first();
    }

    public static function chapterMasterFullSource(?int $userId = null): ?string
    {
        $userId = $userId ?: Auth::id();
        if (!$userId) {
            return null;
        }
        if (self::hasPackage('mission_720', $userId)) {
            return 'mission_720';
        }
        if (self::adminGrantedFullChapterMaster($userId)) {
            return 'admin';
        }

        return null;
    }

    public static function chapterExamLabel($chapter): string
    {
        if (!$chapter) {
            return 'Chapter';
        }
        $name = $chapter->name ?? 'Chapter';
        $level = (int) ($chapter->class_level ?? 0);
        if ($level === 11) {
            $name .= ' (Class 11)';
        } elseif ($level === 12) {
            $name .= ' (Class 12)';
        }
        $subject = $chapter->subject->name ?? 'Subject';

        return $subject.' · '.$name;
    }

    public static function ownedChapterRows(?int $userId = null)
    {
        $userId = $userId ?: Auth::id();
        if (!$userId) {
            return collect();
        }

        return CoursePackageOrderChapter::query()
            ->with(['chapter.subject'])
            ->where('user_id', $userId)
            ->whereHas('order', fn ($q) => $q->whereIn('payment_status', ['SUCCESS', 'FREE']))
            ->orderBy('id')
            ->get();
    }

    public static function ownedChapterIds(?int $userId = null): array
    {
        $userId = $userId ?: Auth::id();
        if (!$userId) {
            return [];
        }

        return CoursePackageOrderChapter::query()
            ->where('user_id', $userId)
            ->whereHas('order', fn ($q) => $q->whereIn('payment_status', ['SUCCESS', 'FREE']))
            ->pluck('chapter_id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    public static function ownsChapter(int $chapterId, ?int $userId = null): bool
    {
        if ($chapterId < 1) {
            return false;
        }
        if (self::chapterMasterFullAccess($userId)) {
            return true;
        }

        return in_array($chapterId, self::ownedChapterIds($userId), true);
    }

    /**
     * Active chapters grouped by subject for the Chapter Master cart.
     *
     * @param  array<int>  $ownedIds
     */
    public static function chapterCatalog(array $ownedIds = [], bool $examReadyOnly = false): array
    {
        $ownedLookup = array_flip($ownedIds);
        $hasClass = Schema::hasColumn('chapters', 'class_level');

        $subjects = Subject::query()
            ->where('status', 1)
            ->where(function ($q) {
                $q->where('is_deleted', 0)->orWhereNull('is_deleted');
            })
            ->orderByRaw("FIELD(name, 'Physics', 'Chemistry', 'Biology', 'Botany', 'Zoology')")
            ->orderBy('name')
            ->get(['id', 'name']);

        if ($subjects->isEmpty()) {
            return [];
        }

        $chapterQuery = Chapter::query()
            ->where('status', 1)
            ->whereIn('subject_id', $subjects->pluck('id'));
        if ($hasClass) {
            $chapterQuery->orderBy('class_level');
        }
        $chapters = $chapterQuery->orderBy('name')->get($hasClass
            ? ['id', 'subject_id', 'name', 'class_level']
            : ['id', 'subject_id', 'name']);

        $bySubject = $chapters->groupBy('subject_id');

        return $subjects->map(function ($subject) use ($bySubject, $ownedLookup, $examReadyOnly) {
            $rows = ($bySubject->get($subject->id) ?? collect())->map(function ($chapter) use ($ownedLookup) {
                $name = $chapter->name;
                $level = (int) ($chapter->class_level ?? 0);
                if ($level === 11) {
                    $name .= ' (Class 11)';
                } elseif ($level === 12) {
                    $name .= ' (Class 12)';
                }

                return [
                    'id' => (int) $chapter->id,
                    'name' => $name,
                    'questions' => \App\Models\Question_source::chapterMasterDisplayCount((int) $chapter->id),
                    'owned' => isset($ownedLookup[(int) $chapter->id]),
                ];
            })->when($examReadyOnly, function ($rows) {
                return $rows->filter(fn ($chapter) => $chapter['owned'] || $chapter['questions'] > 0);
            })->values()->all();

            return [
                'id' => (int) $subject->id,
                'name' => $subject->name,
                'chapters' => $rows,
            ];
        })->filter(fn ($group) => count($group['chapters']) > 0)->values()->all();
    }

    public static function chapterMasterPackage(): ?CoursePackage
    {
        return CoursePackage::active()->where('code', 'chapter_master')->first();
    }

    /** Paid course-package codes that unlock premium question practice. */
    public static function premiumPackageCodes(): array
    {
        return [
            'chapter_master',
            'rank_builder',
            'subject_pro',
            'rank_champion',
            'omr_master',
            'mission_720',
        ];
    }

    /** Packages that open the Chapter Master (subject → chapter → topic) UI. */
    public static function onlinePracticeCodes(): array
    {
        return ['chapter_master'];
    }

    /**
     * Structured test-series packages (schedule-driven).
     * Online CBT + optional OMR after purchase — Rank Builder, Champion, Mission 720.
     */
    public static function seriesPackageCodes(): array
    {
        return [
            'rank_builder',
            'rank_champion',
            'mission_720',
        ];
    }

    /** Online products an admin can assign to a student. */
    public static function assignableOnlineCodes(): array
    {
        return [
            'chapter_master',
            'rank_builder',
            'rank_champion',
            'mission_720',
        ];
    }

    public static function assignableOnlinePackages()
    {
        $codes = self::assignableOnlineCodes();

        return CoursePackage::active()
            ->with('activeModes')
            ->whereIn('code', $codes)
            ->orderByRaw("FIELD(code, '".implode("','", $codes)."')")
            ->get();
    }

    public static function ownedSeriesPackages(?int $userId = null)
    {
        return self::ownedSeriesOrders($userId)
            ->pluck('package')
            ->filter()
            ->unique('id')
            ->sortBy('sort_order')
            ->values();
    }

    /** All Series Tests tabs, with which ones the student can open. */
    public static function seriesTabState(?int $userId = null): array
    {
        $tabs = self::assignableOnlinePackages();
        $owned = [];
        foreach ($tabs as $package) {
            $has = $package->code === 'chapter_master'
                ? self::hasChapterMaster($userId)
                : self::hasPackage($package->code, $userId);
            if ($has) {
                $owned[] = $package->code;
            }
        }

        return [
            'seriesTabs' => $tabs,
            'ownedTabCodes' => $owned,
        ];
    }

    public static function grantOnlineAccess(int $userId, CoursePackage $package, string $note = 'Admin granted online access'): ?CoursePackageOrder
    {
        if (!in_array($package->code, self::assignableOnlineCodes(), true)) {
            return null;
        }

        // Prefer an existing full Chapter Master grant; do not treat Mission 720 as a
        // substitute order — admin may still assign Chapter Master explicitly (e.g. when
        // Mission 720 auto-enables the included packages).
        if ($package->code === 'chapter_master' && self::adminGrantedFullChapterMaster($userId)) {
            return CoursePackageOrder::query()
                ->where('user_id', $userId)
                ->whereIn('payment_status', ['SUCCESS', 'FREE'])
                ->where('course_package_id', $package->id)
                ->whereDoesntHave('orderChapters')
                ->where(function ($q) {
                    $q->where('payment_status', 'FREE')
                        ->orWhere('transaction_id', 'ADMIN-GRANT')
                        ->orWhere('final_amount', '<=', 0);
                })
                ->latest('id')
                ->first();
        }

        if ($package->code !== 'chapter_master' && self::hasPackage($package->code, $userId)) {
            return CoursePackageOrder::query()
                ->where('user_id', $userId)
                ->whereIn('payment_status', ['SUCCESS', 'FREE'])
                ->where('course_package_id', $package->id)
                ->latest('id')
                ->first();
        }

        $mode = self::onlineModeForPackage($package);
        $amount = (float) ($mode?->price ?? $package->display_price ?? 0);
        $tax = (float) ($package->tax ?? 0);
        $final = max(0, $amount + $tax);

        return CoursePackageOrder::create([
            'user_id' => $userId,
            'course_package_id' => $package->id,
            'course_package_mode_id' => $mode?->id,
            'mode_code' => $mode?->mode_code ?? 'online',
            'mode_label' => ($mode?->label ?? 'Online').' · Admin grant',
            'amount' => $amount,
            'tax' => $tax,
            'final_amount' => $final,
            'fulfillment_status' => 'access_granted',
            // FREE = no gateway charge; amount still stores catalogue price for reporting.
            'payment_status' => 'FREE',
            'transaction_id' => 'ADMIN-GRANT',
            'admin_notes' => $note,
            'gateway_meta' => [
                'source' => 'admin_assigned',
                'listed_price' => $final,
            ],
        ]);
    }

    /**
     * Admin assigns specific Chapter Master chapters (max CHAPTER_PICK_LIMIT per call).
     * Skips chapters the student already owns. Does not grant full Chapter Master.
     *
     * @param  array<int>  $chapterIds
     * @return array{order?: CoursePackageOrder, granted: array<int>, error?: string}
     */
    public static function grantChapterMasterChapters(int $userId, array $chapterIds, string $note = 'Admin granted Chapter Master chapters'): array
    {
        $package = self::chapterMasterPackage();
        if (!$package) {
            return ['granted' => [], 'error' => 'Rank Chapter Master package not found.'];
        }

        if (self::chapterMasterFullAccess($userId)) {
            return ['granted' => [], 'error' => 'Student already has full Chapter Master access.'];
        }

        $ids = array_values(array_unique(array_filter(array_map('intval', $chapterIds))));
        $limit = self::CHAPTER_PICK_LIMIT;

        if (count($ids) < 1) {
            return ['granted' => [], 'error' => 'Select at least one chapter (up to '.$limit.').'];
        }
        if (count($ids) > $limit) {
            return ['granted' => [], 'error' => 'You can assign up to '.$limit.' chapters at a time.'];
        }

        $owned = array_flip(self::ownedChapterIds($userId));
        $ids = array_values(array_filter($ids, fn ($id) => !isset($owned[$id])));
        if ($ids === []) {
            return ['granted' => [], 'error' => 'All selected chapters are already assigned.'];
        }

        $chapters = Chapter::query()
            ->whereIn('id', $ids)
            ->where('status', 1)
            ->whereHas('subject', function ($q) {
                $q->where('status', 1)->where(function ($inner) {
                    $inner->where('is_deleted', 0)->orWhereNull('is_deleted');
                });
            })
            ->get();

        if ($chapters->count() !== count($ids)) {
            return ['granted' => [], 'error' => 'One of the selected chapters is not available.'];
        }

        $mode = self::onlineModeForPackage($package);
        $unit = (float) ($mode?->price ?? $package->display_price ?? 0);
        $count = $chapters->count();
        $amount = $unit * $count;
        $tax = (float) ($package->tax ?? 0);
        $final = max(0, $amount + $tax);

        $order = CoursePackageOrder::create([
            'user_id' => $userId,
            'course_package_id' => $package->id,
            'course_package_mode_id' => $mode?->id,
            'mode_code' => $mode?->mode_code ?? 'online',
            'mode_label' => ($mode?->label ?? 'Online').' · Admin grant',
            'amount' => $amount,
            'tax' => $tax,
            'final_amount' => $final,
            'fulfillment_status' => 'access_granted',
            'payment_status' => 'FREE',
            'transaction_id' => 'ADMIN-GRANT',
            'admin_notes' => $note,
            'gateway_meta' => [
                'source' => 'admin_assigned_chapters',
                'chapter_ids' => $chapters->pluck('id')->map(fn ($id) => (int) $id)->values()->all(),
                'unit_price' => $unit,
            ],
        ]);

        $granted = [];
        foreach ($chapters as $chapter) {
            CoursePackageOrderChapter::create([
                'course_package_order_id' => $order->id,
                'user_id' => $userId,
                'chapter_id' => $chapter->id,
                'price' => $unit,
            ]);
            $granted[] = (int) $chapter->id;
        }

        return ['order' => $order, 'granted' => $granted];
    }

    public static function revokeOnlineAccess(int $userId, CoursePackage $package, string $note = 'Admin revoked online access'): int
    {
        return CoursePackageOrder::query()
            ->where('user_id', $userId)
            ->where('course_package_id', $package->id)
            ->whereIn('payment_status', ['SUCCESS', 'FREE'])
            ->update([
                'payment_status' => 'FAILED',
                'fulfillment_status' => 'revoked',
                'admin_notes' => $note,
            ]);
    }

    public static function onlineModeForPackage(CoursePackage $package): ?CoursePackageMode
    {
        $modes = $package->relationLoaded('activeModes')
            ? $package->activeModes
            : $package->activeModes()->get();

        return $modes
            ->sortBy(function (CoursePackageMode $mode) {
                return match ($mode->mode_code) {
                    'online' => 1,
                    'bundle' => 2,
                    default => 3,
                };
            })
            ->first(fn (CoursePackageMode $mode) => (bool) $mode->includes_online)
            ?: $modes->first();
    }

    public static function hasSeriesPackage(string $code, ?int $userId = null): bool
    {
        if (!in_array($code, self::seriesPackageCodes(), true)) {
            return false;
        }

        return self::hasPackage($code, $userId);
    }

    public static function hasAnySeriesPackage(?int $userId = null): bool
    {
        $userId = $userId ?: Auth::id();
        if (!$userId) {
            return false;
        }

        return CoursePackageOrder::query()
            ->where('user_id', $userId)
            ->whereIn('payment_status', ['SUCCESS', 'FREE'])
            ->whereHas('package', function ($q) {
                $q->whereIn('code', self::seriesPackageCodes())->where('status', 1);
            })
            ->exists();
    }

    /** Paid series packages owned by the user (with order). */
    public static function ownedSeriesOrders(?int $userId = null)
    {
        $userId = $userId ?: Auth::id();
        if (!$userId) {
            return collect();
        }

        return CoursePackageOrder::query()
            ->with('package')
            ->where('user_id', $userId)
            ->whereIn('payment_status', ['SUCCESS', 'FREE'])
            ->whereHas('package', function ($q) {
                $q->whereIn('code', self::seriesPackageCodes())->where('status', 1);
            })
            ->orderByDesc('id')
            ->get()
            ->unique('course_package_id')
            ->values();
    }

    public static function schedulesQueryForPackage(CoursePackage $package)
    {
        $scope = $package->schedule_scope;
        $query = CourseSchedule::active()->orderBy('test_date')->orderBy('seq');

        if (!$scope || $scope === 'none' || $scope === 'chapter') {
            return $query->whereRaw('1 = 0');
        }

        if ($scope === 'part') {
            return $query->where('section', 'part');
        }

        if ($scope === 'full') {
            return $query->where('section', 'full');
        }

        // both / anything else → part + full
        return $query->whereIn('section', ['part', 'full']);
    }

    public static function packageUsesPremiumBank(?CoursePackage $package): bool
    {
        if (!$package) {
            return false;
        }

        return $package->code !== self::FREE_CUSTOM_CODE
            && in_array($package->code, self::premiumPackageCodes(), true);
    }

    public static function hasOnlineChapterPractice(?int $userId = null): bool
    {
        $userId = $userId ?: Auth::id();
        if (!$userId) {
            return false;
        }

        return self::hasChapterMaster($userId);
    }

    public static function hasAnyPremiumPackage(?int $userId = null): bool
    {
        $userId = $userId ?: Auth::id();
        if (!$userId) {
            return false;
        }

        return CoursePackageOrder::query()
            ->where('user_id', $userId)
            ->whereIn('payment_status', ['SUCCESS', 'FREE'])
            ->whereHas('package', function ($q) {
                $q->whereIn('code', self::premiumPackageCodes())->where('status', 1);
            })
            ->exists();
    }
}
