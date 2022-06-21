<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Account\AccountController;

//Route::group(['middleware' => ['auth', 'auth_user', 'module_check']], function () {
//    // Account Managing
//    Route::get('/accounts', [AccountController::class, '_viewAccountsGet'])->name('accounts');
//    Route::get('/accounts/{account}', [AccountController::class, '_viewAccountGet'])->name('accounts.account');
//    Route::get('/ajax/view/accounts', [AccountController::class, '_ajaxViewAccountsGet'])->name('accounts.ajax');
//    Route::post('/ajax/make/accounts', [AccountController::class, '_ajaxMakeAccountsPost'])->name('accounts.create.ajax');
//    Route::get('/ajax/delete/accounts', [AccountController::class, '_ajaxDeleteAccountsGet'])->name('accounts.delete.ajax');
//    Route::get('/ajax/view/password', [AccountController::class, '_ajaxViewAccountsPasswordGet'])->name('accounts.password.view.ajax');
//});