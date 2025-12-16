<?php
/*
* Nombre de la clase         : DashboardController.php
* Descripción de la clase    : Controlador para gestionar el panel de control principal
*                               del sistema, mostrando estadísticas y resumen de datos.
* Fecha de creación          : 23/11/2024
* Elaboró                    : Alan Osvaldo Basilio Delgado
* Fecha de liberación        : 27/11/2024
* Autorizó                   : Maileth Patiño Ensastegui
* Versión                    : 1.0
* Fecha de mantenimiento     :
* Folio de mantenimiento     :
* Tipo de mantenimiento      :
* Descripción del mantenimiento :
* Responsable                :
* Revisor                    :
*/

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