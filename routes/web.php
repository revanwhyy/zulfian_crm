<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;

// Public homepage - product catalog
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/buy', [HomeController::class, 'buy'])->name('buy');

// Authentication
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');

    // Projects for manager and sales (with role-based filtering inside controller)
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects/{project}/status', [ProjectController::class, 'updateStatus'])->name('projects.updateStatus');
    Route::post('/projects/{project}/assign', [ProjectController::class, 'assign'])->name('projects.assign')->middleware('manager');

    // Customers view
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');

    // Products CRUD - manager only
    Route::middleware('manager')->group(function () {
        Route::resource('products', ProductController::class)->except(['show']);

        // Users CRUD - manager manages sales accounts
        Route::resource('users', UserController::class)->except(['show']);
    });
});
