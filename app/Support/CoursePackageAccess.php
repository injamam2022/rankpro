<?php

namespace App\Support;

use App\Models\CoursePackage;
use App\Models\CoursePackageOrder;
use App\Models\CourseSchedule;
use Illuminate\Support\Facades\Auth;

/**
 * Question banks are split into two segments:
 * - Custom Test (existing free builder) → non-premium questions only
 * - Course Packages (Chapter Master, Rank Builder, etc.) → premium questions only
 */
class CoursePackageAccess
{
    /** Free Custom Test Builder — does NOT use the premium bank. */
    public const FREE_CUSTOM_CODE = 'free_custom';

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
        return self::hasPackage('chapter_master', $userId);
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
