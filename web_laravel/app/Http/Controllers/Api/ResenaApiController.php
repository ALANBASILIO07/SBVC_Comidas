<?php
/*
* Nombre de la clase         : ResenaApiController.php
* Descripción de la clase    : Controlador API para gestionar reseñas y calificaciones de establecimientos
*                               desde la app móvil.
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
use App\Models\Resena;
use App\Models\Establecimientos;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ResenaApiController extends Controller
{
    /**
     * Obtener reseñas de un establecimiento específico
     *
     * @param int $id
     * @return JsonResponse
     */
    public function porEstablecimiento(int $id): JsonResponse
    {
        $establecimiento = Establecimientos::find($id);

        if (!$establecimiento) {
            return response()->json([
                'message' => 'Establecimiento no encontrado',
            ], 404);
        }

        $resenas = Resena::where('establecimiento_id', $id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'data' => $resenas,
            'meta' => [
                'establecimiento_id'   => $id,
                'total_resenas'        => $resenas->count(),
                'valoracion_promedio'  => $establecimiento->valoracion_promedio,
            ],
        ], 200);
    }

    /**
     * Crear una nueva reseña para un establecimiento
     * Requiere autenticación
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'establecimiento_id' => ['required', 'exists:establecimientos,id'],
                'calificacion'       => ['required', 'integer', 'min:1', 'max:5'],
                'comentario'         => ['nullable', 'string', 'max:500'],
            ]);

            $establecimiento = Establecimientos::find($data['establecimiento_id']);

            if (!$establecimiento) {
                return response()->json([
                    'message' => 'Establecimiento no encontrado',
                ], 404);
            }

            // Verificar si el usuario ya ha dejado una reseña
            $resenaExistente = Resena::where('establecimiento_id', $data['establecimiento_id'])
                ->where('usuario_id', $request->user()->id)
                ->first();

            if ($resenaExistente) {
                return response()->json([
                    'message' => 'Ya has dejado una reseña para este establecimiento',
                ], 422);
            }

            // Crear la reseña
            $resena = Resena::create([
                'establecimiento_id' => $data['establecimiento_id'],
                'usuario_id'         => $request->user()->id,
                'calificacion'       => $data['calificacion'],
                'comentario'         => $data['comentario'] ?? null,
            ]);

            // Actualizar valoración del establecimiento
            $establecimiento->actualizarValoracion($data['calificacion']);

            return response()->json([
                'message' => 'Reseña creada exitosamente',
                'data'    => $resena,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors'  => $e->errors(),
            ], 422);
        }
    }

    /**
     * Actualizar una reseña existente
     * Requiere autenticación y ser el autor de la reseña
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $resena = Resena::find($id);

            if (!$resena) {
                return response()->json([
                    'message' => 'Reseña no encontrada',
                ], 404);
            }

            // Verificar que el usuario es el autor de la reseña
            if ($resena->usuario_id !== $request->user()->id) {
                return response()->json([
                    'message' => 'No tienes permiso para actualizar esta reseña',
                ], 403);
            }

            $data = $request->validate([
                'calificacion' => ['required', 'integer', 'min:1', 'max:5'],
                'comentario'   => ['nullable', 'string', 'max:500'],
            ]);

            $resena->update($data);

            // Recalcular valoración del establecimiento
            $establecimiento = $resena->establecimiento;
            $promedioActual = Resena::where('establecimiento_id', $establecimiento->id)
                ->avg('calificacion');

            $establecimiento->update([
                'valoracion_promedio' => round($promedioActual, 2),
            ]);

            return response()->json([
                'message' => 'Reseña actualizada exitosamente',
                'data'    => $resena->fresh(),
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors'  => $e->errors(),
            ], 422);
        }
    }

    /**
     * Eliminar una reseña
     * Requiere autenticación y ser el autor de la reseña
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $resena = Resena::find($id);

        if (!$resena) {
            return response()->json([
                'message' => 'Reseña no encontrada',
            ], 404);
        }

        // Verificar que el usuario es el autor de la reseña
        if ($resena->usuario_id !== $request->user()->id) {
            return response()->json([
                'message' => 'No tienes permiso para eliminar esta reseña',
            ], 403);
        }

        $establecimientoId = $resena->establecimiento_id;
        $resena->delete();

        // Recalcular valoración del establecimiento
        $establecimiento = Establecimientos::find($establecimientoId);
        $totalResenas = Resena::where('establecimiento_id', $establecimientoId)->count();
        $promedioActual = Resena::where('establecimiento_id', $establecimientoId)->avg('calificacion');

        $establecimiento->update([
            'valoracion_promedio' => $promedioActual ? round($promedioActual, 2) : 0,
            'total_resenas'       => $totalResenas,
        ]);

        return response()->json([
            'message' => 'Reseña eliminada exitosamente',
        ], 200);
    }
}
