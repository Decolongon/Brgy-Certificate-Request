<?php

use App\Http\Controllers\Admin\BrgyCertificateController;
use App\Http\Controllers\Admin\CertificateRequestController;
use App\Http\Controllers\Resident\CertiRequestController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified', 'role:admin|resident'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    //for admin role
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('brgy-certificates', BrgyCertificateController::class)->names([
            'index' => 'brgy-certificates.index',
            'create' => 'brgy-certificates.create',
            'store' => 'brgy-certificates.store',
            'edit' => 'brgy-certificates.edit',
            'update' => 'brgy-certificates.update',
            'destroy' => 'brgy-certificates.destroy',
        ]);

        Route::resource('certificate-requests', CertificateRequestController::class)->names([
            'index' => 'certificate-requests.index',
            'create' => 'certificate-requests.create',
            'store' => 'certificate-requests.store',
            'edit' => 'certificate-requests.edit',
            'update' => 'certificate-requests.update',
            'destroy' => 'certificate-requests.destroy',
        ]);
    });

    //for resident role
    Route::middleware(['role:resident'])->group(function () {
        Route::resource('resident/certificate-requests', CertiRequestController::class)->names([
            'index' => 'resident.certificate-requests.index',
            'store' => 'resident.certificate-requests.store',
            'destroy' => 'resident.certificate-requests.destroy',
        ])->only(['index', 'store', 'destroy']);
    });


});




require __DIR__ . '/settings.php';
