<?php

use App\Http\Controllers\Web\User\UserController;
use App\Http\Controllers\Web\User\UserSettingController;
use Illuminate\Support\Facades\Route;

/*
|-----------------------------------------------------------------------------------------------------------------------
| User Routes
|-----------------------------------------------------------------------------------------------------------------------
|
| The routes that will interact in some way with the user; viewing their dashboards, viewing their user page, being
| able to change their password, view their module access and more.
|
*/

Route::get('/dashboard', [UserController::class, '_viewUserDashboardGet'])->name('user.dashboard');
Route::get('/users',     [UserController::class, '_viewUsersGet']);
Route::get('/user/{id}', [UserController::class, '_viewUserGet']);

Route::get('/ajax/collapse', [UserSettingController::class, '_ajaxSetSidebarCollapsed'])
    ->name('user.settings.sidebar-collapse');