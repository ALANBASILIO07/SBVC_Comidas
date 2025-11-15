<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BannerController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('completar-registro', function () {
    return view('clientes.complete_profile');
})->middleware(['auth'])->name('registro.completar');

// Ruta POST para procesar el formulario (necesitarás crear el controlador)
Route::post('completar-registro', [ClienteController::class, 'store'])
    ->middleware(['auth'])
    ->name('clientes.store');

Route::middleware(['auth', 'verified'])->group(function () {
    // Establecimientos
    Route::get('establecimientos', function () {
        return view('establecimientos.index');
    })->name('establecimientos.index');
    
    Route::get('establecimientos/create', function () {
        return view('establecimientos.create');
    })->name('establecimientos.create');
    
    Route::get('establecimientos/{id}', function ($id) {
        return view('establecimientos.show', ['id' => $id]);
    })->name('establecimientos.show');
    
    Route::get('establecimientos/{id}/edit', function ($id) {
        return view('establecimientos.edit', ['id' => $id]);
    })->name('establecimientos.edit');
    
    // Promociones
    Route::get('promociones', function () {
        return view('promociones.index');
    })->name('promociones.index');
    
    Route::get('promociones/create', function () {
        return view('promociones.create');
    })->name('promociones.create');
    
    Route::get('promociones/{id}', function ($id) {
        return view('promociones.show', ['id' => $id]);
    })->name('promociones.show');
    
    Route::get('promociones/{id}/edit', function ($id) {
        return view('promociones.edit', ['id' => $id]);
    })->name('promociones.edit');
    
    // Banners
    Route::resource('banners', BannerController::class);
    
    // Notificaciones
    Route::get('notificaciones', function () {
        return view('notificaciones.index');
    })->name('notificaciones.index');
    
    Route::get('notificaciones/create', function () {
        return view('notificaciones.create');
    })->name('notificaciones.create');
    
    Route::get('notificaciones/{id}', function ($id) {
        return view('notificaciones.show', ['id' => $id]);
    })->name('notificaciones.show');
    
    Route::get('notificaciones/{id}/edit', function ($id) {
        return view('notificaciones.edit', ['id' => $id]);
    })->name('notificaciones.edit');
    
    //calificaciones
    Route::get('calificaciones', function () {
        return view('calificaciones.index');
    })->name('calificaciones.index');

    // Subscripción
    Route::get('subscripcion', function () {
        return view('subscripcion.index');
    })->name('subscripcion.index');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});