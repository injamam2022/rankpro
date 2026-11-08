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
        return self::chapterMasterLegacyFull($userId) || count(self::ownedChapterIds($userId)) > 0;
    }

    /**
     * Orders placed before per-chapter checkout have no chapter rows.
     * Those buyers keep every chapter.
     */
    public static function chapterMasterLegacyFull(?int $userId = null): bool
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
            ->exists();
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
        if (self::chapterMasterLegacyFull($userId)) {
            return true;
        }

        return in_array($chapterId, self::ownedChapterIds($userId), true);
    }

    /**
     * Active chapters grouped by subject for the Chapter Master cart.
     *
     * @param  array<int>  $ownedIds
     */
    public static function chapterCatalog(array $ownedIds = []): array
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

        return $subjects->map(function ($subject) use ($bySubject, $ownedLookup) {
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

        if ($package->code === 'chapter_master' && self::chapterMasterLegacyFull($userId)) {
            return CoursePackageOrder::query()
                ->where('user_id', $userId)
                ->whereIn('payment_status', ['SUCCESS', 'FREE'])
                ->where('course_package_id', $package->id)
                ->whereDoesntHave('orderChapters')
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

        return CoursePackageOrder::create([
            'user_id' => $userId,
            'course_package_id' => $package->id,
            'course_package_mode_id' => $mode?->id,
            'mode_code' => $mode?->mode_code ?? 'online',
            'mode_label' => ($mode?->label ?? 'Online').' · Admin grant',
            'amount' => 0,
            'final_amount' => 0,
            'fulfillment_status' => 'access_granted',
            'payment_status' => 'FREE',
            'transaction_id' => 'ADMIN-GRANT',
            'admin_notes' => $note,
        ]);
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

        return CoursePackageOrder::query()
            ->where('user_id', $userId)
            ->whereIn('payment_status', ['SUCCESS', 'FREE'])
            ->whereHas('package', function ($q) {
                $q->whereIn('code', self::onlinePracticeCodes())->where('status', 1);
            })
            ->exists();
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
