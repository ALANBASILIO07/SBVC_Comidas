<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Establecimientos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EstablecimientoController extends Controller
{
    /**
     * Listar todos los establecimientos
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = Establecimientos::with(['categoria', 'cliente']);

            // Filtros opcionales
            if ($request->has('activo')) {
                $query->where('activo', $request->activo);
            }

            if ($request->has('verificado')) {
                $query->where('verificacion_establecimiento', $request->verificado);
            }

            if ($request->has('categoria_id')) {
                $query->where('categoria_id', $request->categoria_id);
            }

            if ($request->has('tipo')) {
                $query->where('tipo_establecimiento', $request->tipo);
            }

            if ($request->has('municipio')) {
                $query->where('municipio', 'like', '%' . $request->municipio . '%');
            }

            // Búsqueda cercana por coordenadas
            if ($request->has('lat') && $request->has('lng')) {
                $radio = $request->input('radio', 5); // Radio por defecto 5 km
                $query->cercanos($request->lat, $request->lng, $radio);
            }

            // Ordenamiento
            if ($request->has('ordenar_por')) {
                switch ($request->ordenar_por) {
                    case 'valoracion':
                        $query->mejorValorados();
                        break;
                    case 'recientes':
                        $query->orderByDesc('created_at');
                        break;
                    case 'nombre':
                        $query->orderBy('nombre_establecimiento');
                        break;
                }
            } else {
                $query->orderByDesc('created_at');
            }

            // Paginación
            $perPage = $request->input('per_page', 15);
            $establecimientos = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $establecimientos
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener establecimientos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un nuevo establecimiento
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre_establecimiento' => 'required|string|max:255',
                'tipo_establecimiento' => 'required|in:formal,informal',
                'direccion_completa_establecimiento' => 'required|string',
                'telefono_establecimiento' => 'nullable|string|max:20',
                'correo_establecimiento' => 'nullable|email',
                'categoria_id' => 'required|exists:categorias,id',
                'lat' => 'nullable|numeric',
                'lng' => 'nullable|numeric',
                'imagen_portada' => 'nullable|image|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();
            
            // Manejo de imagen de portada
            if ($request->hasFile('imagen_portada')) {
                $path = $request->file('imagen_portada')->store('establecimientos', 'public');
                $data['imagen_portada'] = $path;
            }

            // Establecer cliente_id del usuario autenticado si tiene cliente
            $user = $request->user();
            if ($user && $user->tieneCliente()) {
                $data['cliente_id'] = $user->cliente->id;
            }

            $establecimiento = Establecimientos::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Establecimiento creado exitosamente',
                'data' => $establecimiento->load(['categoria', 'cliente'])
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear establecimiento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar un establecimiento específico
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $establecimiento = Establecimientos::with(['categoria', 'cliente', 'resenas'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $establecimiento
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Establecimiento no encontrado',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Actualizar un establecimiento
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $establecimiento = Establecimientos::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nombre_establecimiento' => 'sometimes|string|max:255',
                'tipo_establecimiento' => 'sometimes|in:formal,informal',
                'direccion_completa_establecimiento' => 'sometimes|string',
                'telefono_establecimiento' => 'nullable|string|max:20',
                'correo_establecimiento' => 'nullable|email',
                'categoria_id' => 'sometimes|exists:categorias,id',
                'lat' => 'nullable|numeric',
                'lng' => 'nullable|numeric',
                'imagen_portada' => 'nullable|image|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();

            // Manejo de imagen de portada
            if ($request->hasFile('imagen_portada')) {
                // Eliminar imagen anterior si existe
                if ($establecimiento->imagen_portada) {
                    Storage::disk('public')->delete($establecimiento->imagen_portada);
                }
                
                $path = $request->file('imagen_portada')->store('establecimientos', 'public');
                $data['imagen_portada'] = $path;
            }

            $establecimiento->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Establecimiento actualizado exitosamente',
                'data' => $establecimiento->load(['categoria', 'cliente'])
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar establecimiento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un establecimiento (soft delete)
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $establecimiento = Establecimientos::findOrFail($id);
            $establecimiento->delete();

            return response()->json([
                'success' => true,
                'message' => 'Establecimiento eliminado exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar establecimiento',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
