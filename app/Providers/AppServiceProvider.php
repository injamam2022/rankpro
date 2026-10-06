<?php

namespace App\Providers;

use App\View\Composers\StudentShellComposer;
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
        View::composer(
            [
                'site.include.student_left_menu',
                'site.include.student_dashboard_header',
            ],
            StudentShellComposer::class
        );
    }
}
