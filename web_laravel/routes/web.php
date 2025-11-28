<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EstablecimientoController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\PromocionController;

/*
|--------------------------------------------------------------------------
| Web Routes - SIN middleware cliente.completo
|--------------------------------------------------------------------------
*/

// Ruta principal
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas para completar registro de cliente
Route::middleware(['auth'])->group(function () {
    Route::get('completar-registro', function () {
        return view('clientes.complete_profile');
    })->name('registro.completar');
    
    Route::post('completar-registro', [ClienteController::class, 'store'])
        ->name('clientes.store');
});

// Dashboard principal
Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rutas protegidas solo con autenticación y verificación
Route::middleware(['auth', 'verified'])->group(function () {
    
    /*
    |--------------------------------------------------------------------------
    | Establecimientos
    |--------------------------------------------------------------------------
    */
    Route::prefix('establecimientos')->name('establecimientos.')->group(function () {
        Route::get('/', [EstablecimientoController::class, 'index'])->name('index');
        Route::get('/create', [EstablecimientoController::class, 'create'])->name('create');
        Route::post('/', [EstablecimientoController::class, 'store'])->name('store');
        Route::get('/{id}', [EstablecimientoController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [EstablecimientoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [EstablecimientoController::class, 'update'])->name('update');
        Route::delete('/{id}', [EstablecimientoController::class, 'destroy'])->name('destroy');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Promociones
    |--------------------------------------------------------------------------
    */
    Route::resource('promociones', PromocionController::class);
    
    /*
    |--------------------------------------------------------------------------
    | Banners
    |--------------------------------------------------------------------------
    */
    Route::resource('banners', BannerController::class);
    
    /*
    |--------------------------------------------------------------------------
    | Notificaciones
    |--------------------------------------------------------------------------
    */
    Route::prefix('notificaciones')->name('notificaciones.')->group(function () {
        Route::get('/', function () {
            $notificaciones = collect([
                (object)[
                    'id' => 1,
                    'titulo' => 'Nueva promoción disponible',
                    'mensaje' => 'Tu establecimiento tiene una nueva promoción activa',
                    'fecha' => now()->subHours(2),
                    'leida' => false
                ],
                (object)[
                    'id' => 2,
                    'titulo' => 'Calificación recibida',
                    'mensaje' => 'Has recibido una nueva calificación de 5 estrellas',
                    'fecha' => now()->subDays(1),
                    'leida' => true
                ]
            ]);
            return view('notificaciones.index', compact('notificaciones'));
        })->name('index');
        
        Route::get('/create', function () {
            $cliente = auth()->user()->cliente;
            $establecimientos = $cliente 
                ? \App\Models\Establecimientos::where('cliente_id', $cliente->id)->get()
                : collect();
            return view('notificaciones.create', compact('establecimientos'));
        })->name('create');
        
        Route::get('/{id}', function ($id) {
            $notificacion = (object)[
                'id' => $id,
                'titulo' => 'Notificación de ejemplo',
                'mensaje' => 'Este es el contenido detallado de la notificación',
                'fecha' => now(),
                'establecimiento' => 'Restaurante Demo'
            ];
            return view('notificaciones.show', compact('notificacion'));
        })->name('show');
        
        Route::get('/{id}/edit', function ($id) {
            $notificacion = (object)[
                'id' => $id,
                'titulo' => 'Notificación de ejemplo',
                'mensaje' => 'Este es el contenido de la notificación'
            ];
            $establecimientos = \App\Models\Establecimientos::all();
            return view('notificaciones.edit', compact('notificacion', 'establecimientos'));
        })->name('edit');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Calificaciones
    |--------------------------------------------------------------------------
    */
    Route::prefix('calificaciones')->name('calificaciones.')->group(function () {
        Route::get('/', function () {
            $calificaciones = collect([
                (object)[
                    'id' => 1,
                    'establecimiento' => 'Restaurante La Marquesa',
                    'cliente_nombre' => 'Juan Pérez',
                    'puntuacion' => 5,
                    'comentario' => 'Excelente servicio y comida deliciosa',
                    'fecha' => now()->subDays(1)
                ],
                (object)[
                    'id' => 2,
                    'establecimiento' => 'Cafetería Central',
                    'cliente_nombre' => 'María García',
                    'puntuacion' => 4,
                    'comentario' => 'Buen café, ambiente agradable',
                    'fecha' => now()->subDays(2)
                ],
                (object)[
                    'id' => 3,
                    'establecimiento' => 'Food Truck Express',
                    'cliente_nombre' => 'Carlos López',
                    'puntuacion' => 5,
                    'comentario' => 'Rápido y sabroso',
                    'fecha' => now()->subDays(3)
                ]
            ]);
            return view('calificaciones.index', compact('calificaciones'));
        })->name('index');

        Route::get('/create', function () {
            $cliente = auth()->user()->cliente;
            $establecimientos = $cliente 
                ? \App\Models\Establecimientos::where('cliente_id', $cliente->id)->get()
                : collect();
            return view('calificaciones.create', compact('establecimientos'));
        })->name('create');
        
        Route::get('/{id}', function ($id) {
            $calificacion = (object)[
                'id' => $id,
                'establecimiento' => 'Restaurante Demo',
                'cliente_nombre' => 'Cliente Demo',
                'puntuacion' => 5,
                'comentario' => 'Excelente servicio',
                'fecha' => now(),
                'respuesta' => null
            ];
            return view('calificaciones.show', compact('calificacion'));
        })->name('show');
        
        Route::get('/{id}/edit', function ($id) {
            $calificacion = (object)[
                'id' => $id,
                'puntuacion' => 5,
                'comentario' => 'Excelente servicio',
                'respuesta' => 'Gracias por su comentario'
            ];
            return view('calificaciones.edit', compact('calificacion'));
        })->name('edit');
    });

    /*
    |--------------------------------------------------------------------------
    | Subscripción
    |--------------------------------------------------------------------------
    */
    Route::prefix('subscripcion')->name('subscripcion.')->group(function () {
        Route::get('/', function () {
            $cliente = auth()->user()->cliente ?? null;
            $plan_actual = $cliente ? $cliente->plan : 'basico';

            $planes = [
                'basico' => [
                    'nombre' => 'Plan Básico',
                    'precio' => 0,
                    'caracteristicas' => [
                        '1 Establecimiento',
                        '5 Promociones al mes',
                        'Soporte por email'
                    ]
                ],
                'estandar' => [
                    'nombre' => 'Plan Estándar',
                    'precio' => 299,
                    'caracteristicas' => [
                        '1 Establecimiento',
                        'Promociones ilimitadas',
                        'Estadísticas básicas',
                        'Soporte prioritario'
                    ]
                ],
                'premium' => [
                    'nombre' => 'Plan Premium',
                    'precio' => 599,
                    'caracteristicas' => [
                        'Establecimientos ilimitados',
                        'Promociones ilimitadas',
                        'Estadísticas avanzadas',
                        'Soporte 24/7',
                        'API Access'
                    ]
                ]
            ];

            return view('subscripcion.index', compact('cliente', 'plan_actual', 'planes'));
        })->name('index');
    });

    /*
    |--------------------------------------------------------------------------
    | PayPal - Pagos
    |--------------------------------------------------------------------------
    */
    Route::prefix('paypal')->name('paypal.')->group(function () {
        Route::post('/create-order', [PayPalController::class, 'create'])->name('create');
        Route::post('/orders/{orderId}/capture', [PayPalController::class, 'capture'])->name('capture');
    });

    /*
    |--------------------------------------------------------------------------
    | Clientes
    |--------------------------------------------------------------------------
    */
    Route::prefix('clientes')->name('clientes.')->group(function () {
        Route::get('/', function () {
            $clientes = \App\Models\Cliente::with('user')->orderByDesc('created_at')->get();
            return view('clientes.index', compact('clientes'));
        })->name('index');
        
        Route::get('/{id}', function ($id) {
            $cliente = \App\Models\Cliente::with('user')->findOrFail($id);
            return view('clientes.show', compact('cliente'));
        })->name('show');
        
        Route::get('/{id}/edit', function ($id) {
            $cliente = \App\Models\Cliente::findOrFail($id);
            return view('clientes.edit', compact('cliente'));
        })->name('edit');
    });
});

/*
|--------------------------------------------------------------------------
| Configuración de usuario (Settings)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    // Two-factor authentication
    if (class_exists('Laravel\Fortify\Features')) {
        $Features = 'Laravel\Fortify\Features';
        
        if ($Features::enabled($Features::twoFactorAuthentication())) {
            $middlewares = [];
            
            if ($Features::optionEnabled($Features::twoFactorAuthentication(), 'confirmPassword')) {
                $middlewares[] = 'password.confirm';
            }
            
            Volt::route('settings/two-factor', 'settings.two-factor')
                ->middleware($middlewares)
                ->name('two-factor.show');
        } else {
            Route::get('settings/two-factor', function () {
                return redirect()->route('profile.edit')
                    ->with('info', 'La autenticación de dos factores no está habilitada en este momento.');
            })->name('two-factor.show');
        }
    } else {
        Route::get('settings/two-factor', function () {
            return redirect()->route('profile.edit')
                ->with('info', 'La autenticación de dos factores no está disponible.');
        })->name('two-factor.show');
    }
});

/*
|--------------------------------------------------------------------------
| Rutas públicas para términos y privacidad
|--------------------------------------------------------------------------
*/
Route::get('/terminos-y-condiciones', function () {
    return view('legal.terminos');
})->name('terminos');

Route::get('/aviso-de-privacidad', function () {
    return view('legal.privacidad');
})->name('privacidad');