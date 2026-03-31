<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use App\Http\Controllers\VacanteController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\ProgramasGobiernoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- PÁGINA PRINCIPAL ---
Route::get('/', function () {
    return view('home');
})->name('home');

// --- REGISTRO Y LOGIN GENERAL (Candidatas) ---
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// --- LOGOUT ---
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// --- SECCIÓN EMPRESAS ---
Route::get('/login-empresa', function() {
    return view('auth.login-empresa');
})->name('empresa.login');

// Muestra el formulario de registro de empresa
Route::get('/registro-empresa', function(){
    return view('auth.registro-empresa');
})->name('register.empresa');

// Procesa el registro de la empresa en Firebase (Auth + Firestore)
Route::post('/registro-empresa', [RegisterController::class, 'registerEmpresa'])->name('register.empresa.post');

// --- VACANTES ---
Route::get('/vacantes', [VacanteController::class, 'index'])->name('vacantes.index');
Route::get('/publicar-vacante', [VacanteController::class, 'create'])->name('vacantes.crear');
Route::post('/guardar-vacante', [VacanteController::class, 'store'])->name('vacantes.guardar');

// --- APOYOS ---
Route::get('/apoyos-psicologicos', function () {
    return view('auth.apoyos-psicologicos');
})->name('apoyos.psicologicos');

// --- APOYOS GUBERNAMENTALES ---
Route::get('/apoyos-gubernamentales', [ProgramasGobiernoController::class, 'index'])->name('apoyos.gubernamentales');

// --- ADMINISTRADORES (PANEL DE GESTIÓN) ---
Route::get('/admin-gestion', [AdminController::class, 'gestion'])->name('admin.gestion');
Route::patch('/admin-vacantes/toggle/{id}', [AdminController::class, 'toggle'])->name('admin.vacantes.toggle');