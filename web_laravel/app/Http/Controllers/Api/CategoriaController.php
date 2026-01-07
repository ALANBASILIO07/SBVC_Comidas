<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Listar todas las categorías
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = Categoria::query();

            // Filtros opcionales
            if ($request->has('activo')) {
                $query->where('activo', $request->activo);
            }

            if ($request->has('con_conteo')) {
                $query->withCount('establecimientos');
            }

            // Ordenamiento
            $query->orderBy('nombre_categoria');

            $categorias = $query->get();

            return response()->json([
                'success' => true,
                'data' => $categorias
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener categorías',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
