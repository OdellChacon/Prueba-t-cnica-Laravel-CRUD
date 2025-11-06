<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\ServiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Logout simple
Route::get('/logout', function () {
    session()->forget('user');
    session()->regenerate();
    return redirect()->route('home');
})->name('logout.get');

// CRUD Providers
Route::get('providers/trash', [ProviderController::class, 'trash'])->name('providers.trash');
Route::put('providers/{provider}/restore', [ProviderController::class, 'restore'])->name('providers.restore');

// Registrar resource excluyendo 'show' (si no necesitas show en tu controller)
Route::resource('providers', ProviderController::class)->except(['show']);

// Nested CRUD Services under Providers
// <-- moved: register trash/restore BEFORE the shallow resource to avoid /services/{service} hijacking /services/trash
Route::get('services/trash', [ServiceController::class,'trash'])->name('services.trash');
Route::put('services/{id}/restore', [ServiceController::class,'restore'])->name('services.restore');

Route::resource('providers.services', ServiceController::class)->shallow();

// Services trash/restore (shallow)
Route::get('services/trash', [ServiceController::class,'trash'])->name('services.trash');
Route::put('services/{id}/restore', [ServiceController::class,'restore'])->name('services.restore');

// Services (anidados bajo providers para index/create/store, y rutas independientes para edit/update/destroy/trash/restore)
Route::get('/providers/{provider}/services', [ServiceController::class, 'index'])->name('providers.services.index');
Route::get('/providers/{provider}/services/create', [ServiceController::class, 'create'])->name('providers.services.create');
Route::post('/providers/{provider}/services', [ServiceController::class, 'store'])->name('providers.services.store');

Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

// agregar ruta global para "Todos los servicios"
Route::get('/services', [ServiceController::class, 'indexAll'])->name('services.index');
