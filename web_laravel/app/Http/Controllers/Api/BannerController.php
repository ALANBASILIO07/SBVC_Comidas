<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Listar todos los banners
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = Banner::with('establecimiento');

            // Filtros opcionales
            if ($request->has('activo')) {
                $query->where('activo', $request->activo);
            }

            if ($request->has('vigente')) {
                $query->vigentes();
            }

            if ($request->has('establecimiento_id')) {
                $query->where('establecimiento_id', $request->establecimiento_id);
            }

            // Ordenamiento
            $query->orderByDesc('created_at');

            // Paginación
            $perPage = $request->input('per_page', 15);
            $banners = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $banners
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener banners',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un nuevo banner
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'establecimiento_id' => 'required|exists:establecimientos,id',
                'titulo_banner' => 'required|string|max:255',
                'descripcion_banner' => 'nullable|string',
                'imagen_banner' => 'required|image|max:2048',
                'url_destino' => 'nullable|url',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
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
            if ($request->hasFile('imagen_banner')) {
                $path = $request->file('imagen_banner')->store('banners', 'public');
                $data['imagen_banner'] = $path;
            }

            $banner = Banner::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Banner creado exitosamente',
                'data' => $banner->load('establecimiento')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear banner',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar un banner específico
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $banner = Banner::with('establecimiento')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $banner
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Banner no encontrado',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Actualizar un banner
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $banner = Banner::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'establecimiento_id' => 'sometimes|exists:establecimientos,id',
                'titulo_banner' => 'sometimes|string|max:255',
                'descripcion_banner' => 'nullable|string',
                'imagen_banner' => 'nullable|image|max:2048',
                'url_destino' => 'nullable|url',
                'fecha_inicio' => 'sometimes|date',
                'fecha_fin' => 'sometimes|date',
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
            if ($request->hasFile('imagen_banner')) {
                // Eliminar imagen anterior si existe
                if ($banner->imagen_banner) {
                    Storage::disk('public')->delete($banner->imagen_banner);
                }
                
                $path = $request->file('imagen_banner')->store('banners', 'public');
                $data['imagen_banner'] = $path;
            }

            $banner->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Banner actualizado exitosamente',
                'data' => $banner->load('establecimiento')
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar banner',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un banner
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $banner = Banner::findOrFail($id);
            
            // Eliminar imagen si existe
            if ($banner->imagen_banner) {
                Storage::disk('public')->delete($banner->imagen_banner);
            }
            
            $banner->delete();

            return response()->json([
                'success' => true,
                'message' => 'Banner eliminado exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar banner',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
