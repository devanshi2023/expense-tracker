<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Manager\ClaimReviewController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\Team\BudgetController as TeamBudgetController;
use App\Http\Controllers\Team\ExpenseClaimController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::view('/team/dashboard', 'team.dashboard')
        ->middleware('role:team_member,manager,admin')
        ->name('team.dashboard');

    Route::resource('/team/claims', ExpenseClaimController::class)
        ->middleware('role:team_member,manager,admin')
        ->names('team.claims')
        ->except('show');

    Route::get('/team/budgets', TeamBudgetController::class)
        ->middleware('role:team_member,manager,admin')
        ->name('team.budgets.index');

    Route::get('/manager/dashboard', ManagerDashboardController::class)
        ->middleware('role:manager,admin')
        ->name('manager.dashboard');

    Route::patch('/manager/claims/{claim}/review', [ClaimReviewController::class, 'update'])
        ->middleware('role:manager,admin')
        ->name('manager.claims.review');

    Route::view('/admin/dashboard', 'admin.dashboard')
        ->middleware('role:admin')
        ->name('admin.dashboard');
});
