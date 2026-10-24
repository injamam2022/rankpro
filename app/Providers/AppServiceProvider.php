<?php

namespace App\Providers;

use App\View\Composers\StudentShellComposer;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Production MySQL caps index keys at 1000 bytes; 191 utf8mb4 chars keeps string indexes under it.
        Schema::defaultStringLength(191);

        View::composer(
            [
                'site.include.student_left_menu',
                'site.include.student_dashboard_header',
                'site.include.scope_filter_bar',
            ],
            StudentShellComposer::class
        );
    }
}
