<?php

namespace App\Http\Controllers;

use App\Models\Resena;
use App\Models\Establecimientos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CalificacionController extends Controller
{
    /**
     * Mostrar el listado de calificaciones con filtros
     */
    public function index(Request $request)
    {
        // Obtener cliente autenticado
        $cliente = Auth::user()->cliente;

        // Obtener todos los establecimientos del cliente para el filtro
        $establecimientos = Establecimientos::where('cliente_id', $cliente->id)
            ->orderBy('nombre_establecimiento')
            ->get();

        // Query base: solo reseñas de los establecimientos del cliente autenticado
        $query = Resena::with('establecimiento')
            ->whereHas('establecimiento', function($q) use ($cliente) {
                $q->where('cliente_id', $cliente->id);
            })
            ->activas();

        // Filtro por establecimiento
        if ($request->filled('establecimiento')) {
            $query->porEstablecimiento($request->establecimiento);
        }

        // Filtro por puntuación
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

        // Obtener reseñas paginadas
        $resenas = $query->paginate(10)->withQueryString();

        // Calcular estadísticas generales del cliente
        $estadisticas = $this->calcularEstadisticas($cliente->id);

        // Calcular distribución de calificaciones
        $distribucion = $this->calcularDistribucion($cliente->id);

        // Obtener reseñas recientes para el widget
        $resenasRecientes = Resena::with('establecimiento')
            ->whereHas('establecimiento', function($q) use ($cliente) {
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
     * Calcular estadísticas generales
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
     * Calcular distribución de calificaciones (1-5 estrellas)
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

    /**
     * Mostrar todas las reseñas (página completa)
     */
    public function todas(Request $request)
    {
        $cliente = Auth::user()->cliente;

        // Query base
        $query = Resena::with('establecimiento')
            ->whereHas('establecimiento', function($q) use ($cliente) {
                $q->where('cliente_id', $cliente->id);
            })
            ->activas();

        // Filtro por establecimiento
        if ($request->filled('establecimiento')) {
            $query->porEstablecimiento($request->establecimiento);
        }

        // Filtro por puntuación
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
}
