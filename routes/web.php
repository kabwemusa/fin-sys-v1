<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ============ PUBLIC ROUTES (no auth) ============
Route::get('/', App\Livewire\Public\Home::class)->name('home');
Route::get('/loans/{slug}', App\Livewire\Public\LoanProductDetail::class)->name('loan.detail');
Route::get('/apply/{slug}', App\Livewire\Public\ApplicationForm::class)->name('loan.apply');
Route::get('/apply/confirmation/{reference}', App\Livewire\Public\Confirmation::class)->name('loan.confirmation');

// ============ AUTH ROUTES ============
Route::get('/login', App\Livewire\Auth\Login::class)->name('login')->middleware('guest');
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout')->middleware('auth');

// ============ CUSTOMER PORTAL ============
Route::middleware(['auth', 'role:customer', 'force.password.change'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/loans', App\Livewire\Customer\MyLoans::class)->name('loans');
    Route::get('/loans/{reference}', App\Livewire\Customer\LoanDetail::class)->name('loan.detail');
    Route::get('/profile', App\Livewire\Customer\Profile::class)->name('profile');
});

// ============ ADMIN PANEL ============
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', App\Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/applications', App\Livewire\Admin\ApplicationsList::class)->name('applications');
    Route::get('/applications/{id}', App\Livewire\Admin\ApplicationReview::class)->name('application.review');
    Route::get('/customers', App\Livewire\Admin\CustomersList::class)->name('customers');
    Route::get('/customers/{id}', App\Livewire\Admin\CustomerDetail::class)->name('customer.detail');
    Route::get('/products', App\Livewire\Admin\ProductsManager::class)->name('products');
    Route::get('/repayments', App\Livewire\Admin\RepaymentsManager::class)->name('repayments');
    Route::get('/reports', App\Livewire\Admin\Reports::class)->name('reports');
    Route::get('/audit-log', App\Livewire\Admin\AuditLog::class)->name('audit-log');
});

// ============ PASSWORD CHANGE ============
Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', App\Livewire\Auth\ChangePassword::class)->name('password.change');
});
