<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureClienteCompleto
{
    /**
     * Rutas que NO requieren tener el cliente completo
     */
    protected array $exceptRoutes = [
        'registro.completar',
        'clientes.store',
        'logout',
        'profile.edit',
        'user-password.edit',
        'appearance.edit',
        'two-factor.show',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // LOG para debugging
        Log::info('🔍 EnsureClienteCompleto - Verificando', [
            'ruta' => $request->route()?->getName(),
            'url' => $request->url(),
            'autenticado' => Auth::check(),
        ]);

        // Si no está autenticado, dejar pasar (otros middleware se encargan)
        if (!Auth::check()) {
            Log::info('❌ Usuario no autenticado, pasando...');
            return $next($request);
        }

        $user = Auth::user();

        // Si la ruta está en excepciones, permitir acceso
        $currentRoute = $request->route()?->getName();
        if (in_array($currentRoute, $this->exceptRoutes)) {
            Log::info('✅ Ruta exceptuada, permitiendo acceso', ['ruta' => $currentRoute]);
            return $next($request);
        }

        // Verificar si el usuario tiene un cliente asociado
        $tieneCliente = $user->tieneCliente();
        
        Log::info('👤 Estado del cliente', [
            'tiene_cliente' => $tieneCliente,
            'cliente_id' => $user->cliente?->id,
        ]);

        if (!$tieneCliente) {
            Log::warning('⚠️ Usuario sin cliente, redirigiendo a completar registro');
            
            return redirect()->route('registro.completar')
                ->with('warning', 'Debes completar tu registro para acceder a la plataforma.');
        }

        Log::info('✅ Cliente completo, permitiendo acceso');
        return $next($request);
    }
}