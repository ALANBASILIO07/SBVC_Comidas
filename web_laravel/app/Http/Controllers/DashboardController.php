<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard principal.
     */
    public function index()
    {
        $user = Auth::user();

        // --- Lógica del "Get" ---
        
        // TODO: Ajusta estas consultas cuando tus modelos estén listos.
        // Por ahora, usamos valores por defecto para evitar errores.
        
        // Ejemplo de cómo sería (descomenta cuando lo tengas):
        // $establecimientosCount = $user->establecimientos()->where('activo', true)->count();
        // $promocionesCount = $user->promociones()->where('activo', true)->count();
        // $bannersCount = $user->banners()->where('activo', true)->count();
        // $planActual = $user->suscripcion->plan->nombre ?? 'Básico';

        // Valores placeholders (temporales)
        $establecimientosCount = 0;
        $promocionesCount = 0;
        $bannersCount = 0;
        $planActual = 'Básico';


        // Pasamos las variables a la vista 'dashboard.blade.php'
        return view('dashboard', [
            'establecimientosCount' => $establecimientosCount,
            'promocionesCount' => $promocionesCount,
            'bannersCount' => $bannersCount,
            'planActual' => $planActual,
        ]);
    }
}