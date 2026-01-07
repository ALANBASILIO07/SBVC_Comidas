<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CalificacionController extends Controller
{
    /**
     * Listar todas las calificaciones/reseñas
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = Resena::with(['establecimiento', 'cliente']);

            // Filtros opcionales
            if ($request->has('establecimiento_id')) {
                $query->where('establecimiento_id', $request->establecimiento_id);
            }

            if ($request->has('calificacion_min')) {
                $query->where('calificacion', '>=', $request->calificacion_min);
            }

            // Ordenamiento
            $query->orderByDesc('created_at');

            // Paginación
            $perPage = $request->input('per_page', 15);
            $resenas = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $resenas
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener calificaciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear una nueva calificación/reseña
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'establecimiento_id' => 'required|exists:establecimientos,id',
                'calificacion' => 'required|numeric|min:1|max:5',
                'comentario' => 'nullable|string',
                'fotos' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();
            
            // Obtener cliente del usuario autenticado si tiene
            $user = $request->user();
            if ($user && $user->tieneCliente()) {
                $data['cliente_id'] = $user->cliente->id;
            }

            $resena = Resena::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Calificación creada exitosamente',
                'data' => $resena->load(['establecimiento', 'cliente'])
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear calificación',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
