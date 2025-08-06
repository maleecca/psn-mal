<?php

use Illuminate\Support\Facades\Route;

Route::impersonate();

Route::get('/', \App\Livewire\Pelapor\BuatLaporan::class)->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('Petugas')) {
            return redirect()->route('admin-dasbor');
        } elseif (auth()->user()->hasRole('Bidang-Terkait')) {
            return redirect()->route('tindakan');
        } else {
            return redirect()->route('buat-laporan');
        }
    })->name('dashboard');
    Route::get('/admin/dasbor', \App\Livewire\Admin\Dasbor::class)->name('admin-dasbor');
    Route::get('/laporan-masuk', \App\Livewire\Admin\LaporanMasuk::class)->name('laporan-masuk');
    Route::get('/klasifikasi', \App\Livewire\Admin\Klasifikasi::class)->name('klasifikasi');
    Route::get('/bidang-terkait', \App\Livewire\Admin\BidangTerkait::class)->name('bidang-terkait');
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('tindakan', \App\Livewire\BidangTerkait\Tindakan::class)->name('tindakan');
});
Route::get('/buat-laporan', \App\Livewire\Pelapor\BuatLaporan::class)->name('buat-laporan');

