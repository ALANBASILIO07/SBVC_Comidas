<?php

/**
 * Nombre del archivo        : CalificacionController.php
 * Descripción               : Controlador de calificaciones/reseñas del cliente
 * Fecha de creación         : 06/01/2026
 * Elaboró                   : Alan Osvaldo Basilio Delgado
 * Fecha de liberación       : 06/01/2026
 * Autorizó                  : Maileth Patiño Ensastegui
 * Version                   : 1.0
 * Fecha de mantenimiento    : 06/01/2026
 * Folio de mantenimiento    :
 * Tipo de mantenimiento     : Seguridad / UX
 * Descripción del mantenimiento: Implementación de validación de cliente y plan con SweetAlert
 * Responsable               : Alan Osvaldo Basilio Delgado
 * Revisor                   : Maileth Patiño Ensastegui
 */

namespace App\Http\Controllers;

use App\Models\Resena;
use App\Models\Establecimientos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\SweetAlertHelper;

class CalificacionController extends Controller
{
    /**
     * Mostrar el listado de calificaciones con filtros
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $cliente = $user->cliente;

        // Validación redundante (el middleware ya protege, pero por robustez)
        if (!$cliente) {
            return SweetAlertHelper::registroIncompleto('registro.completar');
        }

        if (empty($cliente->plan) || $cliente->plan === 'sin_plan') {
            return SweetAlertHelper::planRequerido('subscripcion.index');
        }

        // Obtener establecimientos del cliente
        $establecimientos = Establecimientos::where('cliente_id', $cliente->id)
            ->orderBy('nombre_establecimiento')
            ->get();

        // Query base de reseñas
        $query = Resena::with('establecimiento')
            ->whereHas('establecimiento', function ($q) use ($cliente) {
                $q->where('cliente_id', $cliente->id);
            })
            ->activas();

        // Filtros
        if ($request->filled('establecimiento')) {
            $query->porEstablecimiento($request->establecimiento);
        }

        if ($request->filled('puntuacion')) {
            $query->porPuntuacion($request->puntuacion);
        }

        // Ordenamiento
        switch ($request->input('orden', 'recientes')) {
            case 'recientes':
                $query->masRecientes();
                break;
            case 'antiguas':
                $query->masAntiguas();
                break;
            case 'mejor':
                $query->mejorCalificadas();
                break;
            case 'peor':
                $query->peorCalificadas();
                break;
            default:
                $query->masRecientes();
        }

        $resenas = $query->paginate(10)->withQueryString();

        // Estadísticas y distribución
        $estadisticas = $this->calcularEstadisticas($cliente->id);
        $distribucion = $this->calcularDistribucion($cliente->id);

        // Reseñas recientes (widget)
        $resenasRecientes = Resena::with('establecimiento')
            ->whereHas('establecimiento', function ($q) use ($cliente) {
                $q->where('cliente_id', $cliente->id);
            })
            ->activas()
            ->masRecientes()
            ->take(4)
            ->get();

        return view('calificaciones.index', compact(
            'resenas',
            'establecimientos',
            'estadisticas',
            'distribucion',
            'resenasRecientes'
        ));
    }

    /**
     * Mostrar todas las calificaciones (vista extendida)
     */
    public function todas(Request $request)
    {
        $user = Auth::user();
        $cliente = $user->cliente;

        // Validación redundante
        if (!$cliente) {
            return SweetAlertHelper::registroIncompleto('registro.completar');
        }

        if (empty($cliente->plan) || $cliente->plan === 'sin_plan') {
            return SweetAlertHelper::planRequerido('subscripcion.index');
        }

        // Query base
        $query = Resena::with('establecimiento')
            ->whereHas('establecimiento', function ($q) use ($cliente) {
                $q->where('cliente_id', $cliente->id);
            })
            ->activas();

        // Filtros
        if ($request->filled('establecimiento')) {
            $query->porEstablecimiento($request->establecimiento);
        }

        if ($request->filled('puntuacion')) {
            $query->porPuntuacion($request->puntuacion);
        }

        // Ordenamiento
        switch ($request->input('orden', 'recientes')) {
            case 'recientes':
                $query->masRecientes();
                break;
            case 'antiguas':
                $query->masAntiguas();
                break;
            case 'mejor':
                $query->mejorCalificadas();
                break;
            case 'peor':
                $query->peorCalificadas();
                break;
        }

        $resenas = $query->paginate(20)->withQueryString();

        $establecimientos = Establecimientos::where('cliente_id', $cliente->id)
            ->orderBy('nombre_establecimiento')
            ->get();

        return view('calificaciones.todas', compact('resenas', 'establecimientos'));
    }

    /**
     * Calcular estadísticas generales de calificaciones
     */
    private function calcularEstadisticas($clienteId)
    {
        $stats = Resena::whereHas('establecimiento', function($q) use ($clienteId) {
                $q->where('cliente_id', $clienteId);
            })
            ->activas()
            ->selectRaw('
                COALESCE(AVG(puntuacion), 0) as promedio,
                COUNT(*) as total,
                SUM(CASE WHEN strftime("%Y-%m", created_at) = strftime("%Y-%m", "now") THEN 1 ELSE 0 END) as este_mes
            ')
            ->first();

        return [
            'promedio' => round($stats->promedio ?? 0, 1),
            'total' => $stats->total ?? 0,
            'este_mes' => $stats->este_mes ?? 0,
        ];
    }

    /**
     * Calcular distribución de calificaciones por estrellas
     */
    private function calcularDistribucion($clienteId)
    {
        $distribucion = Resena::whereHas('establecimiento', function($q) use ($clienteId) {
                $q->where('cliente_id', $clienteId);
            })
            ->activas()
            ->select('puntuacion', DB::raw('count(*) as cantidad'))
            ->groupBy('puntuacion')
            ->orderBy('puntuacion', 'desc')
            ->get()
            ->pluck('cantidad', 'puntuacion')
            ->toArray();

        // Calcular total para porcentajes
        $total = array_sum($distribucion);

        // Generar array completo (1-5) con porcentajes
        $resultado = [];
        for ($i = 5; $i >= 1; $i--) {
            $cantidad = $distribucion[$i] ?? 0;
            $porcentaje = $total > 0 ? round(($cantidad / $total) * 100) : 0;

            $resultado[$i] = [
                'cantidad' => $cantidad,
                'porcentaje' => $porcentaje,
            ];
        }

        return $resultado;
    }
}