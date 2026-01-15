<?php
/*
 * Nombre del archivo        : PromocionController.php
 * Ruta                      : app/Http/Controllers/PromocionController.php
 * Descripción               : Controlador para la gestión de promociones.
 *                            - Valida existencia de establecimientos antes de crear.
 *                            - Todas las notificaciones se emiten mediante SweetAlert (sesión 'swal').
 *                            - Manejo básico de imágenes en disco público.
 * Autor                     : Alan Osvaldo Basilio Delgado
 * Fecha de creación         : 2026-01-14
 * Versión                   : 1.6
 * Responsable               : Alan Osvaldo Basilio Delgado
 * Notas                     : Evitar mensajes de sesión en texto plano; usar siempre 'swal'.
 */

namespace App\Http\Controllers;

use App\Models\Promociones;
use App\Models\Establecimientos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PromocionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('registro.completar')
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => __('Atención'),
                    'text' => __('Primero debes completar tu registro de cliente.'),
                    'confirmButtonColor' => '#F7941D'
                ]);
        }

        $promociones = Promociones::whereHas('establecimiento', function ($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })->with('establecimiento')
          ->orderByDesc('created_at')
          ->get();

        return view('promociones.index', compact('promociones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('registro.completar')
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => __('Atención'),
                    'text' => __('Primero debes completar tu registro de cliente.'),
                    'confirmButtonColor' => '#F7941D'
                ]);
        }

        $establecimientos = Establecimientos::where('cliente_id', $cliente->id)
            ->where('activo', true)
            ->get();

        if ($establecimientos->isEmpty()) {
            // Redirigimos a crear establecimiento y mostramos SweetAlert informativa
            return redirect()->route('establecimientos.create')
                ->with('swal', [
                    'icon' => 'info',
                    'title' => __('Necesitas un establecimiento'),
                    'text' => __('Primero debes crear un establecimiento para poder agregar promociones.'),
                    'confirmButtonColor' => '#F7941D'
                ]);
        }

        return view('promociones.create', compact('establecimientos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('registro.completar')
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => __('Atención'),
                    'text' => __('Primero debes completar tu registro de cliente.'),
                    'confirmButtonColor' => '#F7941D'
                ]);
        }

        $validated = $request->validate([
            'establecimientos_id' => 'required|exists:establecimientos,id',
            'titulo' => 'required|string|min:3|max:255',
            'descripcion' => 'required|string|min:10|max:1000',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_final' => 'required|date|after:fecha_inicio',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'activo' => 'boolean'
        ], [
            'establecimientos_id.required' => __('Debes seleccionar un establecimiento'),
            'establecimientos_id.exists' => __('El establecimiento seleccionado no es válido'),
            'titulo.required' => __('El título es obligatorio'),
            'titulo.min' => __('El título debe tener al menos 3 caracteres'),
            'descripcion.required' => __('La descripción es obligatoria'),
            'descripcion.min' => __('La descripción debe tener al menos 10 caracteres'),
            'fecha_inicio.required' => __('La fecha de inicio es obligatoria'),
            'fecha_inicio.after_or_equal' => __('La fecha de inicio no puede ser anterior a hoy'),
            'fecha_final.required' => __('La fecha final es obligatoria'),
            'fecha_final.after' => __('La fecha final debe ser posterior a la fecha de inicio'),
            'imagen.image' => __('El archivo debe ser una imagen'),
            'imagen.max' => __('La imagen no debe pesar más de 2MB'),
        ]);

        // Verificar que el establecimiento pertenece al cliente
        $establecimiento = Establecimientos::where('id', $validated['establecimientos_id'])
            ->where('cliente_id', $cliente->id)
            ->first();

        if (!$establecimiento) {
            return redirect()->back()
                ->withInput()
                ->with('swal', [
                    'icon' => 'error',
                    'title' => __('Permisos insuficientes'),
                    'text' => __('No tienes permisos para crear promociones en este establecimiento.'),
                    'confirmButtonColor' => '#ef4444'
                ]);
        }

        // Verificar límites según el plan
        $promocionesActivas = Promociones::whereHas('establecimiento', function ($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })->where('activo', true)
          ->whereDate('fecha_final', '>=', now())
          ->count();

        $limitesPorPlan = [
            'basico' => 5,
            'estandar' => 999, // Sin límite práctico
            'premium' => 999,
        ];

        $limite = $limitesPorPlan[$cliente->plan] ?? 5;

        if ($promocionesActivas >= $limite && $cliente->plan === 'basico') {
            return redirect()->route('promociones.index')
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => __('Límite alcanzado'),
                    'text' => __("Has alcanzado el límite de promociones activas para tu plan {$cliente->plan}."),
                    'confirmButtonColor' => '#F7941D'
                ]);
        }

        try {
            $data = $validated;

            // Manejar la imagen si se sube
            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store('promociones', 'public');
                $data['imagen'] = $path;
            }

            // Asegurar que activo tenga un valor booleano
            $data['activo'] = $request->has('activo');

            $promocion = Promociones::create($data);

            return redirect()->route('promociones.index')
                ->with('swal', [
                    'icon' => 'success',
                    'title' => __('¡Éxito!'),
                    'text' => __('Promoción creada exitosamente.'),
                    'confirmButtonColor' => '#42A958'
                ]);
        } catch (\Exception $e) {
            Log::error('Error al crear promoción: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('swal', [
                    'icon' => 'error',
                    'title' => __('¡Error!'),
                    'text' => __('Hubo un error al crear la promoción. Por favor intenta de nuevo.'),
                    'confirmButtonColor' => '#ef4444'
                ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('registro.completar')
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => __('Atención'),
                    'text' => __('Primero debes completar tu registro de cliente.'),
                    'confirmButtonColor' => '#F7941D'
                ]);
        }

        $promocion = Promociones::whereHas('establecimiento', function ($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })->with('establecimiento')
          ->findOrFail($id);

        return view('promociones.show', compact('promocion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('registro.completar')
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => __('Atención'),
                    'text' => __('Primero debes completar tu registro de cliente.'),
                    'confirmButtonColor' => '#F7941D'
                ]);
        }

        $promocion = Promociones::whereHas('establecimiento', function ($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })->findOrFail($id);

        $establecimientos = Establecimientos::where('cliente_id', $cliente->id)
            ->where('activo', true)
            ->get();

        return view('promociones.edit', compact('promocion', 'establecimientos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('registro.completar')
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => __('Atención'),
                    'text' => __('Primero debes completar tu registro de cliente.'),
                    'confirmButtonColor' => '#F7941D'
                ]);
        }

        $promocion = Promociones::whereHas('establecimiento', function ($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })->findOrFail($id);

        $validated = $request->validate([
            'establecimientos_id' => 'required|exists:establecimientos,id',
            'titulo' => 'required|string|min:3|max:255',
            'descripcion' => 'required|string|min:10|max:1000',
            'fecha_inicio' => 'required|date',
            'fecha_final' => 'required|date|after:fecha_inicio',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'activo' => 'boolean'
        ]);

        try {
            $data = $validated;

            // Manejar la imagen si se sube una nueva
            if ($request->hasFile('imagen')) {
                // Eliminar imagen anterior si existe
                if ($promocion->imagen) {
                    Storage::disk('public')->delete($promocion->imagen);
                }

                $path = $request->file('imagen')->store('promociones', 'public');
                $data['imagen'] = $path;
            }

            $data['activo'] = $request->has('activo');

            $promocion->update($data);

            return redirect()->route('promociones.index')
                ->with('swal', [
                    'icon' => 'success',
                    'title' => __('¡Listo!'),
                    'text' => __('Promoción actualizada exitosamente.'),
                    'confirmButtonColor' => '#42A958'
                ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar promoción: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('swal', [
                    'icon' => 'error',
                    'title' => __('¡Error!'),
                    'text' => __('Hubo un error al actualizar la promoción. Por favor intenta de nuevo.'),
                    'confirmButtonColor' => '#ef4444'
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('registro.completar')
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => __('Atención'),
                    'text' => __('Primero debes completar tu registro de cliente.'),
                    'confirmButtonColor' => '#F7941D'
                ]);
        }

        $promocion = Promociones::whereHas('establecimiento', function ($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })->findOrFail($id);

        try {
            // Eliminar imagen si existe
            if ($promocion->imagen) {
                Storage::disk('public')->delete($promocion->imagen);
            }

            $promocion->delete();

            return redirect()->route('promociones.index')
                ->with('swal', [
                    'icon' => 'success',
                    'title' => __('¡Eliminado!'),
                    'text' => __('Promoción eliminada exitosamente.'),
                    'confirmButtonColor' => '#42A958'
                ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar promoción: ' . $e->getMessage());

            return redirect()->back()
                ->with('swal', [
                    'icon' => 'error',
                    'title' => __('¡Error!'),
                    'text' => __('Hubo un error al eliminar la promoción. Por favor intenta de nuevo.'),
                    'confirmButtonColor' => '#ef4444'
                ]);
        }
    }
}