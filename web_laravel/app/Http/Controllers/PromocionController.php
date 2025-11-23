<?php

namespace App\Http\Controllers;

use App\Models\Promociones;
use App\Models\Establecimientos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
                ->with('warning', 'Primero debes completar tu registro de cliente.');
        }

        $promociones = Promociones::whereHas('establecimiento', function($query) use ($cliente) {
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
                ->with('warning', 'Primero debes completar tu registro de cliente.');
        }

        $establecimientos = Establecimientos::where('cliente_id', $cliente->id)
            ->where('activo', true)
            ->get();

        if ($establecimientos->isEmpty()) {
            return redirect()->route('establecimientos.create')
                ->with('info', 'Primero debes crear un establecimiento para poder agregar promociones.');
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
                ->with('warning', 'Primero debes completar tu registro de cliente.');
        }

        $validated = $request->validate([
            'establecimiento_id' => 'required|exists:establecimientos,id',
            'titulo' => 'required|string|min:3|max:255',
            'descripcion' => 'required|string|min:10|max:1000',
            'tipo_promocion' => 'required|in:descuento,2x1,precio_fijo,envio_gratis,otro',
            'valor_descuento' => 'nullable|numeric|min:0|max:100',
            'precio_promocion' => 'nullable|numeric|min:0',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'dias_semana' => 'nullable|array',
            'hora_inicio' => 'nullable|date_format:H:i',
            'hora_fin' => 'nullable|date_format:H:i|after:hora_inicio',
            'terminos_condiciones' => 'nullable|string|max:500',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'activo' => 'boolean'
        ]);

        // Verificar que el establecimiento pertenece al cliente
        $establecimiento = Establecimientos::where('id', $validated['establecimiento_id'])
            ->where('cliente_id', $cliente->id)
            ->first();

        if (!$establecimiento) {
            return redirect()->back()
                ->with('error', 'No tienes permisos para crear promociones en este establecimiento.');
        }

        // Verificar límites según el plan
        $promocionesActivas = Promociones::whereHas('establecimiento', function($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })->where('activo', true)
          ->whereDate('fecha_fin', '>=', now())
          ->count();

        $limitesPorPlan = [
            'basico' => 5,
            'estandar' => 999, // Sin límite
            'premium' => 999, // Sin límite
        ];

        $limite = $limitesPorPlan[$cliente->plan] ?? 5;

        if ($promocionesActivas >= $limite && $cliente->plan === 'basico') {
            return redirect()->route('promociones.index')
                ->with('warning', "Has alcanzado el límite de promociones activas para tu plan {$cliente->plan}.");
        }

        try {
            $data = $validated;
            
            // Manejar la imagen si se sube
            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store('promociones', 'public');
                $data['imagen'] = $path;
            }

            // Asegurar que activo tenga un valor
            $data['activo'] = $request->has('activo');
            
            // Convertir días de la semana a JSON
            if (isset($data['dias_semana'])) {
                $data['dias_semana'] = json_encode($data['dias_semana']);
            }

            $promocion = Promociones::create($data);

            return redirect()->route('promociones.index')
                ->with('success', '¡Promoción creada exitosamente!');

        } catch (\Exception $e) {
            \Log::error('Error al crear promoción: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Hubo un error al crear la promoción. Por favor intenta de nuevo.');
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
                ->with('warning', 'Primero debes completar tu registro de cliente.');
        }

        $promocion = Promociones::whereHas('establecimiento', function($query) use ($cliente) {
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
                ->with('warning', 'Primero debes completar tu registro de cliente.');
        }

        $promocion = Promociones::whereHas('establecimiento', function($query) use ($cliente) {
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
                ->with('warning', 'Primero debes completar tu registro de cliente.');
        }

        $promocion = Promociones::whereHas('establecimiento', function($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })->findOrFail($id);

        $validated = $request->validate([
            'establecimiento_id' => 'required|exists:establecimientos,id',
            'titulo' => 'required|string|min:3|max:255',
            'descripcion' => 'required|string|min:10|max:1000',
            'tipo_promocion' => 'required|in:descuento,2x1,precio_fijo,envio_gratis,otro',
            'valor_descuento' => 'nullable|numeric|min:0|max:100',
            'precio_promocion' => 'nullable|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'dias_semana' => 'nullable|array',
            'hora_inicio' => 'nullable|date_format:H:i',
            'hora_fin' => 'nullable|date_format:H:i|after:hora_inicio',
            'terminos_condiciones' => 'nullable|string|max:500',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

            // Asegurar que activo tenga un valor
            $data['activo'] = $request->has('activo');
            
            // Convertir días de la semana a JSON
            if (isset($data['dias_semana'])) {
                $data['dias_semana'] = json_encode($data['dias_semana']);
            }

            $promocion->update($data);

            return redirect()->route('promociones.index')
                ->with('success', '¡Promoción actualizada exitosamente!');

        } catch (\Exception $e) {
            \Log::error('Error al actualizar promoción: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Hubo un error al actualizar la promoción. Por favor intenta de nuevo.');
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
                ->with('warning', 'Primero debes completar tu registro de cliente.');
        }

        $promocion = Promociones::whereHas('establecimiento', function($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })->findOrFail($id);

        try {
            // Eliminar imagen si existe
            if ($promocion->imagen) {
                Storage::disk('public')->delete($promocion->imagen);
            }

            $promocion->delete();

            return redirect()->route('promociones.index')
                ->with('success', 'Promoción eliminada exitosamente.');

        } catch (\Exception $e) {
            \Log::error('Error al eliminar promoción: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Hubo un error al eliminar la promoción. Por favor intenta de nuevo.');
        }
    }
}