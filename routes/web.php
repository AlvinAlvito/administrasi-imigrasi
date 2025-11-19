<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// ===================
// Halaman Login
// ===================
Route::get('/', function () {
    return view('login'); // landing page + modal login
})->name('login');

// ===================
// Proses Login Manual (update: admin statis, pimpinan & pegawai pakai NIP + password DB)
// ===================
use Illuminate\Support\Facades\Hash;
use App\Models\User;

Route::post('/', function (Request $request) {
    $username = $request->username;
    $password = $request->password;

    // Login admin manual
    if ($username === 'admin' && $password === '123') {
        session([
            'role' => 'admin',
            'is_admin' => true,
            'user_id' => 0
        ]);
        return redirect('/admin');
    }

    // Login pimpinan/pegawai berdasarkan NIP
    $user = User::where('nip', $username)->first();

    if ($user && Hash::check($password, $user->password)) {
        if ($user->role === 'pimpinan') {
            session([
                'role' => 'pimpinan',
                'is_pimpinan' => true,
                'user_id' => $user->id
            ]);
            return redirect('/pimpinan');
        }
        if ($user->role === 'pegawai') {
            session([
                'role' => 'pegawai',
                'is_pegawai' => true,
                'user_id' => $user->id
            ]);
            return redirect('/pegawai');
        }
    }

    return back()->withErrors(['login' => 'NIP atau Password salah!']);
})->name('login.proses');


// ===================
// Logout
// ===================
Route::get('/logout', function () {
    session()->forget(['role','is_admin','is_pimpinan','is_pegawai']);
    return redirect('/');
})->name('logout');

// ===================
// Dashboard Admin
// ===================
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AkunPegawaiController;
use App\Http\Controllers\Admin\AkunPimpinanController;
use App\Http\Controllers\Admin\SuratAdminController;

Route::get('/admin', function () {
    if (!session('is_admin')) return redirect('/');
    return app(AdminDashboardController::class)->index();
})->name('admin.index');

// ===================
// (ADMIN) CRUD Akun Pegawai
// ===================
Route::get('/admin/akun-pegawai', function () {
    if (!session('is_admin')) return redirect('/');
    return app(AkunPegawaiController::class)->index();
})->name('admin.pegawai.index');

Route::post('/admin/akun-pegawai', function (Request $request) {
    if (!session('is_admin')) return redirect('/');
    return app(AkunPegawaiController::class)->store($request);
})->name('admin.pegawai.store');

Route::delete('/admin/akun-pegawai/{id}', function ($id) {
    if (!session('is_admin')) return redirect('/');
    return app(AkunPegawaiController::class)->destroy($id);
})->name('admin.pegawai.destroy');

Route::put('/admin/akun-pegawai/{id}', function (Request $request, $id) {
    if (!session('is_admin')) return redirect('/');
    return app(AkunPegawaiController::class)->update($request, $id);
})->name('admin.pegawai.update');

// ===================
// (ADMIN) CRUD Akun Pimpinan
// ===================
Route::get('/admin/akun-pimpinan', function () {
    if (!session('is_admin')) return redirect('/');
    return app(AkunPimpinanController::class)->index();
})->name('admin.pimpinan.index');

Route::post('/admin/akun-pimpinan', function (Request $request) {
    if (!session('is_admin')) return redirect('/');
    return app(AkunPimpinanController::class)->store($request);
})->name('admin.pimpinan.store');

Route::delete('/admin/akun-pimpinan/{id}', function ($id) {
    if (!session('is_admin')) return redirect('/');
    return app(AkunPimpinanController::class)->destroy($id);
})->name('admin.pimpinan.destroy');

Route::put('/admin/akun-pimpinan/{id}', function (Request $request, $id) {
    if (!session('is_admin')) return redirect('/');
    return app(AkunPimpinanController::class)->update($request, $id);
})->name('admin.pimpinan.update');

// ===================
// (ADMIN) Kelola Semua Surat
// ===================
Route::get('/admin/surat', function () {
    if (!session('is_admin')) return redirect('/');
    return app(SuratAdminController::class)->index();
})->name('admin.surat.index');

// ===================
// (ADMIN) Cetak/Download PDF Surat
// ===================
use App\Http\Controllers\Admin\SuratController as AdminSuratController;

Route::get('/admin/surat/{id}/pdf', function ($id) {
    if (!session('is_admin')) return redirect('/');
    return app(AdminSuratController::class)->pdf($id);
})->name('admin.surat.pdf');

// ===================
// Dashboard Pimpinan
// ===================
use App\Http\Controllers\Pimpinan\DashboardController as PimpinanDashboardController;
use App\Http\Controllers\Pimpinan\SuratApprovalController;

Route::get('/pimpinan', function () {
    if (!session('is_pimpinan')) return redirect('/');
    return app(PimpinanDashboardController::class)->index();
})->name('pimpinan.index');

// ===================
// (PIMPINAN) Daftar Surat & Aksi Terima/Tolak
// ===================
Route::get('/pimpinan/surat', function () {
    if (!session('is_pimpinan')) return redirect('/');
    return app(SuratApprovalController::class)->index();
})->name('pimpinan.surat.index');

Route::post('/pimpinan/surat/{id}/terima', function ($id) {
    if (!session('is_pimpinan')) return redirect('/');
    return app(SuratApprovalController::class)->approve($id);
})->name('pimpinan.surat.approve');

Route::post('/pimpinan/surat/{id}/tolak', function (Request $request, $id) {
    if (!session('is_pimpinan')) return redirect('/');
    return app(SuratApprovalController::class)->reject($request, $id);
})->name('pimpinan.surat.reject');

// ===================
// (PIMPINAN) Profil Saya
// ===================
use App\Http\Controllers\Pimpinan\ProfilController as PimpinanProfilController;

Route::get('/pimpinan/profil', function () {
    if (!session('is_pimpinan')) return redirect('/');
    return app(PimpinanProfilController::class)->show();
})->name('pimpinan.profil');

// ===================
// Dashboard Pegawai
// ===================
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboardController;
use App\Http\Controllers\Pegawai\SuratSayaController;

Route::get('/pegawai', function () {
    if (!session('is_pegawai')) return redirect('/');
    return app(PegawaiDashboardController::class)->index();
})->name('pegawai.index');

// Profiil pegawai
use App\Http\Controllers\Pegawai\ProfilController as PegawaiProfilController;
Route::get('/pegawai/profil', function () {
    if (!session('is_pegawai')) return redirect('/');
    return app(PegawaiProfilController::class)->show();
})->name('pegawai.profil');

// ===================
// (PEGAWAI) CRUD Surat Milik Sendiri + Cetak PDF
// ===================
Route::get('/pegawai/surat', function () {
    if (!session('is_pegawai')) return redirect('/');
    return app(SuratSayaController::class)->index();
})->name('pegawai.surat.index');

Route::post('/pegawai/surat', function (Request $request) {
    if (!session('is_pegawai')) return redirect('/');
    return app(SuratSayaController::class)->store($request);
})->name('pegawai.surat.store');

Route::delete('/pegawai/surat/{id}', function ($id) {
    if (!session('is_pegawai')) return redirect('/');
    return app(SuratSayaController::class)->destroy($id);
})->name('pegawai.surat.destroy');

Route::put('/pegawai/surat/{id}', function (Request $request, $id) {
    if (!session('is_pegawai')) return redirect('/');
    return app(SuratSayaController::class)->update($request, $id);
})->name('pegawai.surat.update');

Route::get('/pegawai/surat/{id}/pdf', function ($id) {
    if (!session('is_pegawai')) return redirect('/');
    return app(SuratSayaController::class)->cetakPdf($id);
})->name('pegawai.surat.pdf');
