<?php
/**
 * Nombre del archivo        : PromocionController.php
 * Ruta                      : app/Http/Controllers/PromocionController.php
 * Descripción               : Controlador para la gestión de promociones.
 * - index(): Carga la vista. No bloquea visualmente por falta de establecimientos.
 * - create(): Valida existencia de establecimientos. Si faltan, redirige con Alerta (Botón Negro).
 * - store/update/destroy: Gestión CRUD con alertas SweetAlert estandarizadas (Verde/Rojo).
 * Autor                     : Alan Osvaldo Basilio Delgado
 * Fecha de creación         : 2026-01-14
 * Versión                   : 2.0 (Fix Color Alerta & Lógica Diferida)
 * Responsable               : Alan Osvaldo Basilio Delgado
 */

namespace App\Http\Controllers;

use App\Models\Promocion; // Asegúrate de que tu modelo sea Promocion o Promociones según tu estructura real. Ajustado a singular estándar.
use App\Models\Promociones; // Mantengo el original por si acaso tu modelo se llama así.
use App\Models\Establecimientos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PromocionController extends Controller
{
    /**
     * Muestra la lista de promociones.
     */
    public function index()
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('registro.completar')
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => 'Atención',
                    'text' => 'Primero debes completar tu registro de cliente.',
                    'confirmButtonColor' => '#000000'
                ]);
        }

        // Usando el modelo correcto (asumo Promociones basado en tu código anterior)
        $promociones = Promociones::whereHas('establecimiento', function ($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })->with('establecimiento')
          ->orderByDesc('created_at')
          ->paginate(10); // Usar paginate es mejor para listas largas

        return view('promociones.index', compact('promociones'));
    }

    /**
     * Muestra el formulario de creación.
     * AQUÍ SE APLICA LA VALIDACIÓN DE ESTABLECIMIENTO.
     */
    public function create()
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('registro.completar');
        }

        $establecimientos = Establecimientos::where('cliente_id', $cliente->id)
            ->where('activo', true) // Opcional: filtrar solo activos
            ->orderBy('nombre_establecimiento')
            ->get();

        // VALIDACIÓN CRÍTICA: Redirección con botón NEGRO
        if ($establecimientos->isEmpty()) {
            return redirect()->route('establecimientos.create')
                ->with('swal', [
                    'icon' => 'info',
                    'title' => 'Necesitas un establecimiento',
                    'text' => 'Primero debes crear un establecimiento para poder agregar promociones.',
                    'confirmButtonText' => 'Crear establecimiento',
                    'confirmButtonColor' => '#000000' // <--- COLOR CORREGIDO (NEGRO)
                ]);
        }

        return view('promociones.create', compact('establecimientos'));
    }

    /**
     * Almacena la promoción.
     */
    public function store(Request $request)
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('registro.completar');
        }

        // Validación
        $validated = $request->validate([
            'establecimientos_id' => 'required|exists:establecimientos,id',
            'titulo' => 'required|string|min:3|max:255',
            'descripcion' => 'required|string|min:10|max:1000',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_final' => 'required|date|after:fecha_inicio',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'activo' => 'boolean'
        ], [
            'establecimientos_id.required' => 'Debes seleccionar un establecimiento',
            'titulo.required' => 'El título es obligatorio',
            // ... resto de mensajes personalizados ...
        ]);

        // Verificar propiedad
        $establecimiento = Establecimientos::where('id', $validated['establecimientos_id'])
            ->where('cliente_id', $cliente->id)
            ->first();

        if (!$establecimiento) {
            return redirect()->back()->withInput()->with('swal', [
                'icon' => 'error',
                'title' => 'Permisos insuficientes',
                'text' => 'No tienes permisos para crear promociones en este establecimiento.',
                'confirmButtonColor' => '#ef4444'
            ]);
        }

        // Lógica de guardado
        try {
            $data = $validated;
            
            // Renombrar llave foránea si el modelo lo requiere (ajusta según tu DB real)
            // $data['establecimiento_id'] = $validated['establecimientos_id']; 

            if ($request->hasFile('imagen')) {
                $data['imagen'] = $request->file('imagen')->store('promociones', 'public');
            }

            $data['activo'] = $request->has('activo');

            Promociones::create($data);

            return redirect()->route('promociones.index')
                ->with('swal', [
                    'icon' => 'success',
                    'title' => '¡Éxito!',
                    'text' => 'Promoción creada exitosamente.',
                    'confirmButtonColor' => '#16a34a' // Verde
                ]);

        } catch (\Exception $e) {
            Log::error('Error store promocion: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'Hubo un error al crear la promoción.',
                'confirmButtonColor' => '#ef4444' // Rojo
            ]);
        }
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit($id)
    {
        $cliente = Auth::user()->cliente;
        
        $promocion = Promociones::whereHas('establecimiento', function ($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })->findOrFail($id);

        $establecimientos = Establecimientos::where('cliente_id', $cliente->id)->get();

        return view('promociones.edit', compact('promocion', 'establecimientos'));
    }

    /**
     * Actualiza la promoción.
     */
    public function update(Request $request, $id)
    {
        $cliente = Auth::user()->cliente;
        
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

            if ($request->hasFile('imagen')) {
                if ($promocion->imagen && Storage::disk('public')->exists($promocion->imagen)) {
                    Storage::disk('public')->delete($promocion->imagen);
                }
                $data['imagen'] = $request->file('imagen')->store('promociones', 'public');
            }

            $data['activo'] = $request->has('activo');

            $promocion->update($data);

            return redirect()->route('promociones.index')
                ->with('swal', [
                    'icon' => 'success',
                    'title' => '¡Actualizado!',
                    'text' => 'Promoción actualizada exitosamente.',
                    'confirmButtonColor' => '#16a34a'
                ]);

        } catch (\Exception $e) {
            Log::error('Error update promocion: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'No se pudo actualizar la promoción.',
                'confirmButtonColor' => '#ef4444'
            ]);
        }
    }

    /**
     * Elimina la promoción.
     */
    public function destroy($id)
    {
        $cliente = Auth::user()->cliente;
        
        $promocion = Promociones::whereHas('establecimiento', function ($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })->findOrFail($id);

        try {
            if ($promocion->imagen && Storage::disk('public')->exists($promocion->imagen)) {
                Storage::disk('public')->delete($promocion->imagen);
            }
            $promocion->delete();

            return redirect()->route('promociones.index')
                ->with('swal', [
                    'icon' => 'success',
                    'title' => 'Eliminado',
                    'text' => 'Promoción eliminada correctamente.',
                    'confirmButtonColor' => '#16a34a'
                ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo eliminar la promoción.',
                'confirmButtonColor' => '#ef4444'
            ]);
        }
    }
}