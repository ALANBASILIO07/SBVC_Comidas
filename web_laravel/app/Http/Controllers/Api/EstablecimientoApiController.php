<?php
/*
* Nombre de la clase         : EstablecimientoApiController.php
* Descripción de la clase    : Controlador API para gestionar establecimientos desde la app móvil,
*                               incluyendo listado, búsqueda, filtros y geolocalización.
* Fecha de creación          : 16/12/2024
* Elaboró                    : Claude Code Assistant
* Fecha de liberación        : 16/12/2024
* Autorizó                   : Alan Basilio
* Versión                    : 1.0
* Fecha de mantenimiento     :
* Folio de mantenimiento     :
* Tipo de mantenimiento      :
* Descripción del mantenimiento :
* Responsable                :
* Revisor                    :
*/

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Establecimientos;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EstablecimientoApiController extends Controller
{
    /**
     * Listar todos los establecimientos activos con filtros opcionales
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Establecimientos::with('categoria')->activos();

        // Filtro por categoría
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Filtro por tipo de establecimiento (formal/informal)
        if ($request->filled('tipo')) {
            $query->where('tipo_establecimiento', $request->tipo);
        }

        // Filtro por valoración mínima
        if ($request->filled('min_rating')) {
            $query->where('valoracion_promedio', '>=', $request->min_rating);
        }

        // Filtro por grado de confianza
        if ($request->filled('min_confianza')) {
            $query->confiables($request->min_confianza);
        }

        // Filtro por método de pago
        if ($request->filled('metodo_pago')) {
            $query->whereJsonContains('tipos_pago_establecimiento', $request->metodo_pago);
        }

        // Filtro por facturación
        if ($request->boolean('facturacion')) {
            $query->where('facturacion_establecimiento', true);
        }

        // Ordenamiento
        $orderBy = $request->input('order_by', 'created_at');
        $orderDirection = $request->input('order_direction', 'desc');

        if ($orderBy === 'valoracion') {
            $query->mejorValorados();
        } else {
            $query->orderBy($orderBy, $orderDirection);
        }

        // Paginación
        $perPage = $request->input('per_page', 15);
        $establecimientos = $query->paginate($perPage);

        return response()->json([
            'data' => $establecimientos->items(),
            'meta' => [
                'current_page'  => $establecimientos->currentPage(),
                'last_page'     => $establecimientos->lastPage(),
                'per_page'      => $establecimientos->perPage(),
                'total'         => $establecimientos->total(),
            ],
        ], 200);
    }

    /**
     * Obtener detalles de un establecimiento específico
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $establecimiento = Establecimientos::with(['categoria', 'resenas'])
            ->activos()
            ->find($id);

        if (!$establecimiento) {
            return response()->json([
                'message' => 'Establecimiento no encontrado',
            ], 404);
        }

        return response()->json([
            'data' => $establecimiento,
        ], 200);
    }

    /**
     * Buscar establecimientos cercanos a una ubicación
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function cercanos(Request $request): JsonResponse
    {
        $request->validate([
            'lat'      => ['required', 'numeric', 'between:-90,90'],
            'lng'      => ['required', 'numeric', 'between:-180,180'],
            'radio_km' => ['nullable', 'numeric', 'min:0.1', 'max:50'],
        ]);

        $lat = $request->lat;
        $lng = $request->lng;
        $radioKm = $request->input('radio_km', 5);

        $establecimientos = Establecimientos::with('categoria')
            ->activos()
            ->cercanos($lat, $lng, $radioKm)
            ->get();

        return response()->json([
            'data' => $establecimientos,
            'meta' => [
                'lat'      => $lat,
                'lng'      => $lng,
                'radio_km' => $radioKm,
                'total'    => $establecimientos->count(),
            ],
        ], 200);
    }

    /**
     * Filtrar establecimientos por categoría
     *
     * @param int $categoriaId
     * @return JsonResponse
     */
    public function porCategoria(int $categoriaId): JsonResponse
    {
        $establecimientos = Establecimientos::with('categoria')
            ->activos()
            ->porCategoria($categoriaId)
            ->mejorValorados()
            ->get();

        return response()->json([
            'data' => $establecimientos,
            'meta' => [
                'categoria_id' => $categoriaId,
                'total'        => $establecimientos->count(),
            ],
        ], 200);
    }

    /**
     * Buscar establecimientos por término de búsqueda
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function buscar(Request $request): JsonResponse
    {
        $request->validate([
            'q' => ['required', 'string', 'min:2'],
        ]);

        $termino = $request->q;

        $establecimientos = Establecimientos::with('categoria')
            ->activos()
            ->where(function ($query) use ($termino) {
                $query->where('nombre_establecimiento', 'like', "%{$termino}%")
                    ->orWhere('direccion_completa_establecimiento', 'like', "%{$termino}%")
                    ->orWhere('municipio', 'like', "%{$termino}%")
                    ->orWhere('colonia', 'like', "%{$termino}%");
            })
            ->mejorValorados()
            ->get();

        return response()->json([
            'data' => $establecimientos,
            'meta' => [
                'termino' => $termino,
                'total'   => $establecimientos->count(),
            ],
        ], 200);
    }
}
