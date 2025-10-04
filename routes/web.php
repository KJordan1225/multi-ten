<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\PreventAccessFromTenantDomains;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware([PreventAccessFromTenantDomains::class])->group(function () {
    Route::get('/', fn () => view('welcome'));
    // central-only routes...
    Route::get('/central/health', fn() => 'central ok');
});

require __DIR__.'/auth.php';
