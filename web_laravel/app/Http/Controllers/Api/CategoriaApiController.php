<?php
/*
* Nombre de la clase         : CategoriaApiController.php
* Descripción de la clase    : Controlador API para gestionar categorías de establecimientos desde la app móvil.
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
use App\Models\Categoria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriaApiController extends Controller
{
    /**
     * Listar todas las categorías activas
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categorias = Categoria::where('activo', true)
            ->orderBy('tipo_establecimiento')
            ->orderBy('nombre')
            ->get();

        // Agrupar por tipo de establecimiento
        $categoriasAgrupadas = $categorias->groupBy('tipo_establecimiento');

        return response()->json([
            'data' => $categorias,
            'agrupadas' => $categoriasAgrupadas,
            'meta' => [
                'total' => $categorias->count(),
            ],
        ], 200);
    }

    /**
     * Obtener categorías agrupadas por tipo
     *
     * @return JsonResponse
     */
    public function agrupadasPorTipo(): JsonResponse
    {
        $categorias = Categoria::where('activo', true)
            ->orderBy('tipo_establecimiento')
            ->orderBy('nombre')
            ->get()
            ->groupBy('tipo_establecimiento');

        $resultado = [];
        foreach ($categorias as $tipo => $items) {
            $resultado[] = [
                'tipo' => $tipo,
                'nombre_tipo' => $tipo === 'formal' ? 'Formal' : 'Informal',
                'categorias' => $items,
                'total' => $items->count(),
            ];
        }

        return response()->json([
            'data' => $resultado,
        ], 200);
    }

    /**
     * Obtener detalles de una categoría específica
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $categoria = Categoria::where('activo', true)->find($id);

        if (!$categoria) {
            return response()->json([
                'message' => 'Categoría no encontrada',
            ], 404);
        }

        // Contar establecimientos en esta categoría
        $totalEstablecimientos = $categoria->establecimientos()
            ->where('activo', true)
            ->count();

        return response()->json([
            'data' => $categoria,
            'meta' => [
                'total_establecimientos' => $totalEstablecimientos,
            ],
        ], 200);
    }
}
