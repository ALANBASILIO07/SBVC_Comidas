<?php
/**
 * Nombre del archivo        : BannerController.php
 * Ruta                      : app/Http/Controllers/BannerController.php
 * Descripción               : Controlador para gestionar Banners.
 * - index(): Carga la vista sin bloquear por falta de establecimientos.
 * - create(): Valida si existen establecimientos. Si no, redirige con SweetAlert.
 * - Valida límites del plan contratado.
 * Fecha de creación         : 2026-01-14
 * Versión                   : 2.3 (Lógica Diferida y Redirección)
 * Responsable               : Alan Osvaldo Basilio Delgado
 */

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Establecimientos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BannerController extends Controller
{
    /**
     * Muestra la lista de banners.
     * No redirige automáticamente; permite ver el estado vacío.
     */
    public function index()
    {
        $cliente = Auth::user()->cliente;

        // Validación de seguridad: Perfil incompleto
        if (!$cliente) {
            return redirect()->route('registro.completar')
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => 'Atención',
                    'text' => 'Primero debes completar tu registro de cliente.',
                    'confirmButtonColor' => '#000000'
                ]);
        }

        // Obtener banners
        $banners = Banner::whereHas('establecimiento', function ($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })->with('establecimiento')
            ->orderByDesc('created_at')
            ->get();

        // Pasamos establecimientos por si se requiere en el futuro, aunque la validación está en create
        $establecimientos = Establecimientos::where('cliente_id', $cliente->id)->get();

        return view('banners.index', compact('banners', 'establecimientos'));
    }

    /**
     * Muestra el formulario para crear un nuevo banner.
     * AQUÍ se valida la existencia de establecimientos.
     */
    public function create(Request $request)
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('registro.completar');
        }

        $establecimientos = Establecimientos::where('cliente_id', $cliente->id)
            ->orderBy('nombre_establecimiento')
            ->get();

        // 1. VALIDACIÓN CRÍTICA: Si no hay establecimientos, redirigir
        if ($establecimientos->isEmpty()) {
            $payload = [
                'icon' => 'info',
                'title' => 'Necesitas un establecimiento',
                'text' => 'Para poder crear banners, primero debes registrar al menos un establecimiento.',
                'confirmButtonText' => 'Crear establecimiento',
                'confirmButtonColor' => '#000000' // Negro por consistencia
            ];

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'redirect' => route('establecimientos.create'),
                    'swal' => $payload
                ], 403);
            }

            return redirect()->route('establecimientos.create')->with('swal', $payload);
        }

        // 2. Validar límite de banners según plan
        $plan = strtolower($cliente->plan ?? 'sin_plan');
        $limits = $this->planLimits($plan);
        $limiteBanners = $limits['banners'] ?? 0;

        $bannersCount = Banner::whereHas('establecimiento', function ($q) use ($cliente) {
            $q->where('cliente_id', $cliente->id);
        })->count();

        if ($limiteBanners > 0 && $bannersCount >= $limiteBanners) {
            $mensaje = 'Has alcanzado el límite de banners para tu plan actual.';
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => $mensaje], 403);
            }

            return redirect()->route('banners.index')
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => 'Límite alcanzado',
                    'text' => $mensaje,
                    'confirmButtonColor' => '#f59e0b'
                ]);
        }

        return view('banners.create', compact('establecimientos'));
    }

    /**
     * Almacena un nuevo banner en la base de datos.
     */
    public function store(Request $request)
    {
        try {
            $cliente = Auth::user()->cliente;

            $validated = $request->validate([
                'establecimiento_id' => 'required|exists:establecimientos,id',
                'titulo_banner' => 'required|string|min:3|max:255',
                'descripcion_banner' => 'nullable|string|max:500',
                'imagen_banner' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'url_destino' => 'nullable|url|max:500',
                'fecha_inicio' => 'required|date|after_or_equal:today',
                'fecha_fin' => 'required|date|after:fecha_inicio',
                'activo' => 'boolean'
            ]);

            // Verificar propiedad
            $establecimiento = Establecimientos::findOrFail($validated['establecimiento_id']);
            if ($establecimiento->cliente_id !== $cliente->id) {
                return redirect()->back()->with('swal', [
                    'icon' => 'error',
                    'title' => 'Error de permiso',
                    'text' => 'El establecimiento seleccionado no te pertenece.',
                    'confirmButtonColor' => '#ef4444'
                ]);
            }

            $data = $validated;

            if ($request->hasFile('imagen_banner')) {
                $path = $request->file('imagen_banner')->store('banners', 'public');
                $data['imagen_banner'] = $path;
            }

            $data['activo'] = $request->has('activo');

            Banner::create($data);

            return redirect()->route('banners.index')
                ->with('swal', [
                    'icon' => 'success',
                    'title' => '¡Éxito!',
                    'text' => 'Banner creado exitosamente.',
                    'confirmButtonColor' => '#16a34a'
                ]);

        } catch (\Exception $e) {
            Log::error('Error al crear banner: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Hubo un problema al guardar el banner.',
                'confirmButtonColor' => '#ef4444'
            ]);
        }
    }

    /**
     * Muestra el formulario para editar un banner.
     */
    public function edit(Banner $banner)
    {
        $cliente = Auth::user()->cliente;

        if ($banner->establecimiento->cliente_id !== $cliente->id) {
            return redirect()->route('banners.index')->with('swal', [
                'icon' => 'error',
                'title' => 'Acceso denegado',
                'text' => 'No tienes permisos para editar este banner.',
                'confirmButtonColor' => '#ef4444'
            ]);
        }

        $establecimientos = Establecimientos::where('cliente_id', $cliente->id)
            ->orderBy('nombre_establecimiento')
            ->get();

        return view('banners.edit', compact('banner', 'establecimientos'));
    }

    /**
     * Actualiza un banner específico.
     */
    public function update(Request $request, Banner $banner)
    {
        try {
            $cliente = Auth::user()->cliente;

            if ($banner->establecimiento->cliente_id !== $cliente->id) {
                abort(403);
            }

            $validated = $request->validate([
                'establecimiento_id' => 'required|exists:establecimientos,id',
                'titulo_banner' => 'required|string|min:3|max:255',
                'descripcion_banner' => 'nullable|string|max:500',
                'imagen_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'url_destino' => 'nullable|url|max:500',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after:fecha_inicio',
                'activo' => 'boolean'
            ]);

            $data = $validated;

            if ($request->hasFile('imagen_banner')) {
                if ($banner->imagen_banner && Storage::disk('public')->exists($banner->imagen_banner)) {
                    Storage::disk('public')->delete($banner->imagen_banner);
                }
                $data['imagen_banner'] = $request->file('imagen_banner')->store('banners', 'public');
            }

            $data['activo'] = $request->has('activo');

            $banner->update($data);

            return redirect()->route('banners.index')
                ->with('swal', [
                    'icon' => 'success',
                    'title' => '¡Actualizado!',
                    'text' => 'Banner actualizado correctamente.',
                    'confirmButtonColor' => '#16a34a'
                ]);

        } catch (\Exception $e) {
            Log::error('Error update banner: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo actualizar el banner.',
                'confirmButtonColor' => '#ef4444'
            ]);
        }
    }

    /**
     * Elimina un banner.
     */
    public function destroy(Request $request, Banner $banner)
    {
        $cliente = Auth::user()->cliente;

        if ($banner->establecimiento->cliente_id !== $cliente->id) {
            abort(403);
        }

        try {
            if ($banner->imagen_banner && Storage::disk('public')->exists($banner->imagen_banner)) {
                Storage::disk('public')->delete($banner->imagen_banner);
            }
            $banner->delete();

            return redirect()->route('banners.index')
                ->with('swal', [
                    'icon' => 'success',
                    'title' => 'Eliminado',
                    'text' => 'El banner ha sido eliminado.',
                    'confirmButtonColor' => '#16a34a'
                ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo eliminar el banner.',
                'confirmButtonColor' => '#ef4444'
            ]);
        }
    }

    private function planLimits(string $plan): array
    {
        $plan = strtolower($plan);
        $defaults = [
            'sin_plan' => ['banners' => 0],
            'basico'   => ['banners' => 3],
            'premium'  => ['banners' => 10],
        ];
        return $defaults[$plan] ?? $defaults['sin_plan'];
    }
}