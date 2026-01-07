<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Establecimientos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Muestra la lista de banners del usuario autenticado.
     */
    public function index()
    {
        $cliente = Auth::user();

        // Obtener todos los banners de los establecimientos del cliente
        $banners = Banner::whereHas('establecimiento', function($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })->with('establecimiento')
          ->orderByDesc('created_at')
          ->get();

        return view('banners.index', compact('banners'));
    }

    /**
     * Muestra el formulario para crear un nuevo banner.
     */
    public function create()
    {
        $cliente = Auth::user();

        // Obtener los establecimientos del cliente autenticado
        $establecimientos = Establecimientos::where('cliente_id', $cliente->id)
            ->orderBy('nombre_establecimiento')
            ->get();

        // Verificar que el cliente tenga establecimientos
        if ($establecimientos->isEmpty()) {
            return redirect()->route('establecimientos.index')
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => 'Sin establecimientos',
                    'text' => 'Primero debes crear un establecimiento antes de agregar banners',
                    'confirmButtonText' => 'Entendido',
                    'confirmButtonColor' => '#f59e0b',
                    'draggable' => true
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
            $cliente = Auth::user();

            // Validar datos
            $validated = $request->validate([
                'establecimiento_id' => 'required|exists:establecimientos,id',
                'titulo_banner' => 'required|string|min:3|max:255',
                'descripcion_banner' => 'nullable|string|max:500',
                'imagen_banner' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
                'url_destino' => 'nullable|url|max:500',
                'fecha_inicio' => 'required|date|after_or_equal:today',
                'fecha_fin' => 'required|date|after:fecha_inicio',
                'activo' => 'boolean'
            ], [
                'establecimiento_id.required' => 'Debes seleccionar un establecimiento',
                'establecimiento_id.exists' => 'El establecimiento seleccionado no existe',
                'titulo_banner.required' => 'El título es obligatorio',
                'titulo_banner.min' => 'El título debe tener al menos 3 caracteres',
                'descripcion_banner.max' => 'La descripción no puede exceder 500 caracteres',
                'imagen_banner.required' => 'La imagen es obligatoria',
                'imagen_banner.image' => 'El archivo debe ser una imagen',
                'imagen_banner.max' => 'La imagen no puede pesar más de 5MB',
                'fecha_inicio.required' => 'La fecha de inicio es obligatoria',
                'fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior a hoy',
                'fecha_fin.required' => 'La fecha de finalización es obligatoria',
                'fecha_fin.after' => 'La fecha de finalización debe ser posterior a la fecha de inicio',
                'url_destino.url' => 'Debes ingresar una URL válida'
            ]);

            // Verificar que el establecimiento pertenezca al cliente
            $establecimiento = Establecimientos::findOrFail($validated['establecimiento_id']);
            if ($establecimiento->cliente_id !== $cliente->id) {
                return redirect()->back()
                    ->with('swal', [
                        'icon' => 'error',
                        'title' => '¡Error!',
                        'text' => 'No tienes permisos para crear banners en este establecimiento',
                        'confirmButtonText' => 'Entendido',
                        'confirmButtonColor' => '#ef4444',
                        'draggable' => true
                    ]);
            }

            $data = $validated;

            // Procesar imagen
            if ($request->hasFile('imagen_banner')) {
                $path = $request->file('imagen_banner')->store('banners', 'public');
                $data['imagen_banner'] = $path;
            }

            // Asegurar que activo tenga un valor
            $data['activo'] = $request->has('activo');

            Banner::create($data);

            return redirect()->route('banners.index')
                ->with('swal', [
                    'icon' => 'success',
                    'title' => '¡Éxito!',
                    'text' => '¡Banner creado exitosamente!',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#42A958',
                    'draggable' => true
                ]);

        } catch (\Exception $e) {
            \Log::error('Error al crear banner: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('swal', [
                    'icon' => 'error',
                    'title' => '¡Error!',
                    'text' => 'Hubo un error al crear el banner: ' . $e->getMessage(),
                    'confirmButtonText' => 'Entendido',
                    'confirmButtonColor' => '#ef4444',
                    'draggable' => true
                ]);
        }
    }

    /**
     * Muestra un banner específico.
     */
    public function show(Banner $banner)
    {
        $cliente = Auth::user();

        // Verificar que el banner pertenezca al cliente
        if ($banner->establecimiento->cliente_id !== $cliente->id) {
            abort(403, 'No tienes permisos para ver este banner');
        }

        return view('banners.show', compact('banner'));
    }

    /**
     * Muestra el formulario para editar un banner.
     */
    public function edit(Banner $banner)
    {
        $cliente = Auth::user();

        // Verificar que el banner pertenezca al cliente
        if ($banner->establecimiento->cliente_id !== $cliente->id) {
            return redirect()->route('banners.index')
                ->with('swal', [
                    'icon' => 'error',
                    'title' => '¡Error!',
                    'text' => 'No tienes permisos para editar este banner',
                    'confirmButtonText' => 'Entendido',
                    'confirmButtonColor' => '#ef4444',
                    'draggable' => true
                ]);
        }

        // Obtener establecimientos del cliente
        $establecimientos = Establecimientos::where('cliente_id', $cliente->id)
            ->orderBy('nombre_establecimiento')
            ->get();

        return view('banners.edit', compact('banner', 'establecimientos'));
    }

    /**
     * Actualiza un banner específico en la base de datos.
     */
    public function update(Request $request, Banner $banner)
    {
        try {
            $cliente = Auth::user();

            // Verificar que el banner pertenezca al cliente
            if ($banner->establecimiento->cliente_id !== $cliente->id) {
                return redirect()->route('banners.index')
                    ->with('swal', [
                        'icon' => 'error',
                        'title' => '¡Error!',
                        'text' => 'No tienes permisos para actualizar este banner',
                        'confirmButtonText' => 'Entendido',
                        'confirmButtonColor' => '#ef4444',
                        'draggable' => true
                    ]);
            }

            // Validar datos
            $validated = $request->validate([
                'establecimiento_id' => 'required|exists:establecimientos,id',
                'titulo_banner' => 'required|string|min:3|max:255',
                'descripcion_banner' => 'nullable|string|max:500',
                'imagen_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'url_destino' => 'nullable|url|max:500',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after:fecha_inicio',
                'activo' => 'boolean'
            ], [
                'establecimiento_id.required' => 'Debes seleccionar un establecimiento',
                'titulo_banner.required' => 'El título es obligatorio',
                'titulo_banner.min' => 'El título debe tener al menos 3 caracteres',
                'imagen_banner.image' => 'El archivo debe ser una imagen',
                'imagen_banner.max' => 'La imagen no puede pesar más de 5MB',
                'fecha_fin.after' => 'La fecha de finalización debe ser posterior a la fecha de inicio'
            ]);

            $data = $validated;

            // Procesar nueva imagen si se subió
            if ($request->hasFile('imagen_banner')) {
                // Eliminar imagen anterior
                if ($banner->imagen_banner && Storage::disk('public')->exists($banner->imagen_banner)) {
                    Storage::disk('public')->delete($banner->imagen_banner);
                }

                $path = $request->file('imagen_banner')->store('banners', 'public');
                $data['imagen_banner'] = $path;
            }

            // Asegurar que activo tenga un valor
            $data['activo'] = $request->has('activo');

            $banner->update($data);

            return redirect()->route('banners.index')
                ->with('swal', [
                    'icon' => 'success',
                    'title' => '¡Éxito!',
                    'text' => '¡Banner actualizado exitosamente!',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#42A958',
                    'draggable' => true
                ]);

        } catch (\Exception $e) {
            \Log::error('Error al actualizar banner: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('swal', [
                    'icon' => 'error',
                    'title' => '¡Error!',
                    'text' => 'Hubo un error al actualizar el banner: ' . $e->getMessage(),
                    'confirmButtonText' => 'Entendido',
                    'confirmButtonColor' => '#ef4444',
                    'draggable' => true
                ]);
        }
    }

    /**
     * Elimina un banner de la base de datos.
     */
    public function destroy(Banner $banner)
    {
        try {
            $cliente = Auth::user();

            // Verificar que el banner pertenezca al cliente
            if ($banner->establecimiento->cliente_id !== $cliente->id) {
                return redirect()->route('banners.index')
                    ->with('swal', [
                        'icon' => 'error',
                        'title' => '¡Error!',
                        'text' => 'No tienes permisos para eliminar este banner',
                        'confirmButtonText' => 'Entendido',
                        'confirmButtonColor' => '#ef4444',
                        'draggable' => true
                    ]);
            }

            // Eliminar imagen del storage
            if ($banner->imagen_banner && Storage::disk('public')->exists($banner->imagen_banner)) {
                Storage::disk('public')->delete($banner->imagen_banner);
            }

            $banner->delete();

            return redirect()->route('banners.index')
                ->with('swal', [
                    'icon' => 'success',
                    'title' => '¡Eliminado!',
                    'text' => 'Banner eliminado exitosamente',
                    'confirmButtonText' => 'Aceptar',
                    'confirmButtonColor' => '#42A958',
                    'draggable' => true
                ]);

        } catch (\Exception $e) {
            \Log::error('Error al eliminar banner: ' . $e->getMessage());

            return redirect()->back()
                ->with('swal', [
                    'icon' => 'error',
                    'title' => '¡Error!',
                    'text' => 'Hubo un error al eliminar el banner: ' . $e->getMessage(),
                    'confirmButtonText' => 'Entendido',
                    'confirmButtonColor' => '#ef4444',
                    'draggable' => true
                ]);
        }
    }
}
