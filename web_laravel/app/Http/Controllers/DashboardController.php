<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Establecimientos;
use App\Models\Promociones;
use App\Models\Banner;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard principal del usuario.
     */
    public function index()
    {
        $user = Auth::user();
        $cliente = $user->cliente;

        // Determinar si el registro está completo
        $registroCompleto = $user->tieneCliente();

        if ($registroCompleto) {
            // Usuario con cliente completo - mostrar estadísticas
            $establecimientosCount = Establecimientos::where('cliente_id', $cliente->id)->count();

            $promocionesCount = Promociones::whereHas('establecimiento', function($query) use ($cliente) {
                $query->where('cliente_id', $cliente->id);
            })->count();

            $bannersCount = Banner::whereHas('establecimiento', function($query) use ($cliente) {
                $query->where('cliente_id', $cliente->id);
            })->count();

            $planActual = $this->formatearPlan($cliente->plan ?? 'basico');
            $planRaw = $cliente->plan ?? 'basico';
        } else {
            // Usuario sin cliente - mostrar ceros y plan por defecto
            $establecimientosCount = 0;
            $promocionesCount = 0;
            $bannersCount = 0;
            $planActual = 'Sin plan';
            $planRaw = 'basico';
        }

        return view('dashboard.index', compact(
            'establecimientosCount',
            'promocionesCount',
            'bannersCount',
            'planActual',
            'planRaw',
            'registroCompleto'
        ));
    }

    /**
     * Formatea el nombre del plan para mostrar
     */
    private function formatearPlan(?string $plan): string
    {
        if (!$plan) {
            return 'Estándar';
        }

        $planes = [
            'basico' => 'Plan Básico',
            'estandar' => 'Plan Estándar',
            'premium' => 'Plan Premium',
        ];

        return $planes[$plan] ?? ucfirst($plan);
    }
}