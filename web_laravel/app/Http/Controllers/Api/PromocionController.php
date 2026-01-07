<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promociones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PromocionController extends Controller
{
    /**
     * Listar todas las promociones
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = Promociones::with('establecimiento');

            // Filtros opcionales
            if ($request->has('activo')) {
                $query->where('activo', $request->activo);
            }

            if ($request->has('vigente')) {
                $query->vigentes();
            }

            if ($request->has('establecimiento_id')) {
                $query->where('establecimientos_id', $request->establecimiento_id);
            }

            if ($request->has('tipo_promocion')) {
                $query->where('tipo_promocion', $request->tipo_promocion);
            }

            // Ordenamiento
            if ($request->has('ordenar_por')) {
                switch ($request->ordenar_por) {
                    case 'recientes':
                        $query->orderByDesc('created_at');
                        break;
                    case 'fecha_inicio':
                        $query->orderBy('fecha_inicio');
                        break;
                    case 'descuento':
                        $query->orderByDesc('valor_descuento');
                        break;
                }
            } else {
                $query->orderByDesc('created_at');
            }

            // Paginación
            $perPage = $request->input('per_page', 15);
            $promociones = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $promociones
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener promociones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear una nueva promoción
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'establecimientos_id' => 'required|exists:establecimientos,id',
                'titulo' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'tipo_promocion' => 'required|in:porcentaje,precio_fijo,2x1,3x2',
                'valor_descuento' => 'nullable|numeric|min:0',
                'precio_promocion' => 'nullable|numeric|min:0',
                'fecha_inicio' => 'required|date',
                'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
                'dias_semana' => 'nullable|array',
                'hora_inicio' => 'nullable|date_format:H:i',
                'hora_fin' => 'nullable|date_format:H:i',
                'terminos_condiciones' => 'nullable|string',
                'imagen' => 'nullable|image|max:2048',
                'activo' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();
            
            // Manejo de imagen
            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store('promociones', 'public');
                $data['imagen'] = $path;
            }

            $promocion = Promociones::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Promoción creada exitosamente',
                'data' => $promocion->load('establecimiento')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear promoción',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar una promoción específica
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $promocion = Promociones::with('establecimiento')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $promocion
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Promoción no encontrada',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Actualizar una promoción
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $promocion = Promociones::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'establecimientos_id' => 'sometimes|exists:establecimientos,id',
                'titulo' => 'sometimes|string|max:255',
                'descripcion' => 'nullable|string',
                'tipo_promocion' => 'sometimes|in:porcentaje,precio_fijo,2x1,3x2',
                'valor_descuento' => 'nullable|numeric|min:0',
                'precio_promocion' => 'nullable|numeric|min:0',
                'fecha_inicio' => 'sometimes|date',
                'fecha_final' => 'sometimes|date',
                'dias_semana' => 'nullable|array',
                'hora_inicio' => 'nullable|date_format:H:i',
                'hora_fin' => 'nullable|date_format:H:i',
                'terminos_condiciones' => 'nullable|string',
                'imagen' => 'nullable|image|max:2048',
                'activo' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();

            // Manejo de imagen
            if ($request->hasFile('imagen')) {
                // Eliminar imagen anterior si existe
                if ($promocion->imagen) {
                    Storage::disk('public')->delete($promocion->imagen);
                }
                
                $path = $request->file('imagen')->store('promociones', 'public');
                $data['imagen'] = $path;
            }

            $promocion->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Promoción actualizada exitosamente',
                'data' => $promocion->load('establecimiento')
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar promoción',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una promoción
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $promocion = Promociones::findOrFail($id);
            
            // Eliminar imagen si existe
            if ($promocion->imagen) {
                Storage::disk('public')->delete($promocion->imagen);
            }
            
            $promocion->delete();

            return response()->json([
                'success' => true,
                'message' => 'Promoción eliminada exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar promoción',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
