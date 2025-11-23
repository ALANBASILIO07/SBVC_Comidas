<?php

namespace App\Http\Controllers;

use App\Models\Establecimientos;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstablecimientoController extends Controller
{
    /**
     * Muestra la lista de establecimientos
     */
    public function index()
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('registro.completar')
                ->with('warning', 'Primero debes completar tu registro de cliente.');
        }

        $establecimientos = Establecimientos::where('cliente_id', $cliente->id)
            ->orderByDesc('created_at')
            ->get();

        return view('establecimientos.index', compact('establecimientos'));
    }

    /**
     * Muestra el formulario de creación
     */
    public function create()
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('registro.completar')
                ->with('warning', 'Primero debes completar tu registro de cliente.');
        }

        // Verificar límite según el plan
        $establecimientosCount = Establecimientos::where('cliente_id', $cliente->id)->count();
        
        $limitesPorPlan = [
            'basico' => 1,
            'estandar' => 1,
            'premium' => 999, // Sin límite
        ];

        $limite = $limitesPorPlan[$cliente->plan] ?? 1;

        if ($establecimientosCount >= $limite) {
            return redirect()->route('establecimientos.index')
                ->with('warning', "Has alcanzado el límite de establecimientos para tu plan {$cliente->plan}. Actualiza tu plan para agregar más.");
        }

        $categorias = Categoria::all();

        return view('establecimientos.create', compact('categorias'));
    }

    /**
     * Guarda el nuevo establecimiento
     */
    public function store(Request $request)
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('registro.completar')
                ->with('warning', 'Primero debes completar tu registro de cliente.');
        }

        // Validar
        $validated = $request->validate([
            'nombre_establecimiento' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'tipo_establecimiento' => 'required|in:Restaurante,Cafetería,Food Truck,Panadería,Bar,Otro',
            'direccion_completa_establecimiento' => 'required|string|max:500',
            'colonia' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'estado' => 'required|string|max:100',
            'codigo_postal' => [
                'required',
                'string',
                'size:5',
                'regex:/^[0-9]{5}$/'
            ],
            'telefono_establecimiento' => [
                'required',
                'string',
                'min:10',
                'max:20',
                'regex:/^[0-9]+$/'
            ],
            'correo_establecimiento' => 'required|email|max:255',
            'rfc_establecimiento' => [
                'nullable',
                'string',
                'size:13',
                'regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/'
            ],
            'razon_social_establecimiento' => 'nullable|string|max:255',
            'direccion_fiscal_establecimiento' => 'nullable|string|max:500',
            'facturacion_establecimiento' => 'boolean',
            'tipos_pago_establecimiento' => 'array',
            'categoria_id' => 'nullable|exists:categorias,id',
        ], [
            'nombre_establecimiento.required' => 'El nombre del establecimiento es obligatorio',
            'nombre_establecimiento.min' => 'El nombre debe tener al menos 3 caracteres',
            'tipo_establecimiento.required' => 'Debes seleccionar un tipo de establecimiento',
            'direccion_completa_establecimiento.required' => 'La dirección es obligatoria',
            'telefono_establecimiento.required' => 'El teléfono es obligatorio',
            'telefono_establecimiento.regex' => 'El teléfono solo puede contener números',
            'correo_establecimiento.required' => 'El correo es obligatorio',
            'correo_establecimiento.email' => 'Debes ingresar un correo válido',
            'codigo_postal.size' => 'El código postal debe tener 5 dígitos',
            'codigo_postal.regex' => 'El código postal solo puede contener números',
            'rfc_establecimiento.size' => 'El RFC debe tener exactamente 13 caracteres',
            'rfc_establecimiento.regex' => 'El formato del RFC no es válido',
        ]);

        try {
            $establecimiento = Establecimientos::create([
                'cliente_id' => $cliente->id,
                'nombre_establecimiento' => $validated['nombre_establecimiento'],
                'tipo_establecimiento' => $validated['tipo_establecimiento'],
                'direccion_completa_establecimiento' => $validated['direccion_completa_establecimiento'],
                'colonia' => $validated['colonia'],
                'municipio' => $validated['municipio'],
                'estado' => $validated['estado'],
                'codigo_postal' => $validated['codigo_postal'],
                'telefono_establecimiento' => $validated['telefono_establecimiento'],
                'correo_establecimiento' => $validated['correo_establecimiento'],
                'rfc_establecimiento' => $validated['rfc_establecimiento'] ?? null,
                'razon_social_establecimiento' => $validated['razon_social_establecimiento'] ?? null,
                'direccion_fiscal_establecimiento' => $validated['direccion_fiscal_establecimiento'] ?? null,
                'verificacion_establecimiento' => false,
                'facturacion_establecimiento' => $request->has('facturacion_establecimiento'),
                'tipos_pago_establecimiento' => $validated['tipos_pago_establecimiento'] ?? [],
                'categoria_id' => $validated['categoria_id'] ?? null,
                'activo' => true,
                'grado_confianza' => 50,
                'cantidad_reportes' => 0,
            ]);

            return redirect()->route('establecimientos.index')
                ->with('success', '¡Establecimiento creado exitosamente! Ahora puedes comenzar a agregar tu menú y promociones.');

        } catch (\Exception $e) {
            \Log::error('Error al crear establecimiento: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Hubo un error al guardar el establecimiento. Por favor intenta de nuevo.');
        }
    }

    /**
     * Muestra el detalle de un establecimiento
     */
    public function show($id)
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('registro.completar')
                ->with('warning', 'Primero debes completar tu registro de cliente.');
        }

        $establecimiento = Establecimientos::where('cliente_id', $cliente->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('establecimientos.show', compact('establecimiento'));
    }

    /**
     * Muestra el formulario de edición
     */
    public function edit($id)
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('registro.completar')
                ->with('warning', 'Primero debes completar tu registro de cliente.');
        }

        $establecimiento = Establecimientos::where('cliente_id', $cliente->id)
            ->where('id', $id)
            ->firstOrFail();
        
        $categorias = Categoria::all();

        return view('establecimientos.edit', compact('establecimiento', 'categorias'));
    }

    /**
     * Actualiza un establecimiento
     */
    public function update(Request $request, $id)
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('registro.completar')
                ->with('warning', 'Primero debes completar tu registro de cliente.');
        }

        $establecimiento = Establecimientos::where('cliente_id', $cliente->id)
            ->where('id', $id)
            ->firstOrFail();

        // Validar
        $validated = $request->validate([
            'nombre_establecimiento' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'tipo_establecimiento' => 'required|in:Restaurante,Cafetería,Food Truck,Panadería,Bar,Otro',
            'direccion_completa_establecimiento' => 'required|string|max:500',
            'colonia' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'estado' => 'required|string|max:100',
            'codigo_postal' => [
                'required',
                'string',
                'size:5',
                'regex:/^[0-9]{5}$/'
            ],
            'telefono_establecimiento' => [
                'required',
                'string',
                'min:10',
                'max:20',
                'regex:/^[0-9]+$/'
            ],
            'correo_establecimiento' => 'required|email|max:255',
            'rfc_establecimiento' => [
                'nullable',
                'string',
                'size:13',
                'regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/'
            ],
            'razon_social_establecimiento' => 'nullable|string|max:255',
            'direccion_fiscal_establecimiento' => 'nullable|string|max:500',
            'facturacion_establecimiento' => 'boolean',
            'tipos_pago_establecimiento' => 'array',
            'categoria_id' => 'nullable|exists:categorias,id',
        ]);

        try {
            $establecimiento->update([
                'nombre_establecimiento' => $validated['nombre_establecimiento'],
                'tipo_establecimiento' => $validated['tipo_establecimiento'],
                'direccion_completa_establecimiento' => $validated['direccion_completa_establecimiento'],
                'colonia' => $validated['colonia'],
                'municipio' => $validated['municipio'],
                'estado' => $validated['estado'],
                'codigo_postal' => $validated['codigo_postal'],
                'telefono_establecimiento' => $validated['telefono_establecimiento'],
                'correo_establecimiento' => $validated['correo_establecimiento'],
                'rfc_establecimiento' => $validated['rfc_establecimiento'] ?? null,
                'razon_social_establecimiento' => $validated['razon_social_establecimiento'] ?? null,
                'direccion_fiscal_establecimiento' => $validated['direccion_fiscal_establecimiento'] ?? null,
                'facturacion_establecimiento' => $request->has('facturacion_establecimiento'),
                'tipos_pago_establecimiento' => $validated['tipos_pago_establecimiento'] ?? [],
                'categoria_id' => $validated['categoria_id'] ?? null,
            ]);

            return redirect()->route('establecimientos.index')
                ->with('success', '¡Establecimiento actualizado exitosamente!');

        } catch (\Exception $e) {
            \Log::error('Error al actualizar establecimiento: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Hubo un error al actualizar el establecimiento. Por favor intenta de nuevo.');
        }
    }

    /**
     * Elimina un establecimiento
     */
    public function destroy($id)
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('registro.completar')
                ->with('warning', 'Primero debes completar tu registro de cliente.');
        }

        $establecimiento = Establecimientos::where('cliente_id', $cliente->id)
            ->where('id', $id)
            ->firstOrFail();

        try {
            $establecimiento->delete();

            return redirect()->route('establecimientos.index')
                ->with('success', 'Establecimiento eliminado exitosamente.');

        } catch (\Exception $e) {
            \Log::error('Error al eliminar establecimiento: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Hubo un error al eliminar el establecimiento. Por favor intenta de nuevo.');
        }
    }
}