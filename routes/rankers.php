<?php

  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Route;

  use App\Http\Middleware\RankerAuth;

  use App\Http\Controllers\Rankers\RankerAuthController;
  use App\Http\Controllers\Rankers\RankerDashboardController;
  use App\Http\Controllers\Rankers\RankerAdminController;
  use App\Http\Controllers\Rankers\RankerAppointmentController;

  Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
  });

  Route::get('/', [RankerAuthController::class, 'index'])->name('rankers.index');
  Route::post('dologin', [RankerAuthController::class, 'dologin'])->name('rankers.dologin');
  
  Route::get('/dashboard', [RankerDashboardController::class, 'index'])->name('rankers.dashboard');
  Route::get('/profile', [RankerAdminController::class, 'index'])->name('rankers.profile');
  Route::post('/update-profile', [RankerAdminController::class, 'updateProfile'])->name('rankers.update_profile');
  Route::get('/profile/change-password', [RankerAdminController::class, 'changePassword'])->name('rankers.change_password');
  Route::post('/profile/update-password', [RankerAdminController::class, 'updatePassword'])->name('rankers.updatePassword');
  Route::get('/profile/logout', [RankerAuthController::class, 'logout'])->name('rankers.logout');

  Route::get('/appointment', [RankerAppointmentController::class, 'list'])->name('rankers.appointment');
 

