<?php

namespace App\Http\Controllers;

use App\Models\Establecimientos;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => 'Registro incompleto',
                    'text' => 'Primero debes completar tu registro de cliente.',
                ]);
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
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => 'Registro incompleto',
                    'text' => 'Primero debes completar tu registro de cliente.',
                ]);
        }

        // Verificar límite según el plan
        $establecimientosCount = Establecimientos::where('cliente_id', $cliente->id)->count();
        
        $limitesPorPlan = [
            'basico' => 1,
            'estandar' => 1,
            'premium' => 999,
        ];

        $limite = $limitesPorPlan[$cliente->plan] ?? 1;

        if ($establecimientosCount >= $limite) {
            return redirect()->route('establecimientos.index')
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => 'Limite alcanzado',
                    'text' => "Has alcanzado el limite de establecimientos para tu plan {$cliente->plan}. Actualiza tu plan para agregar mas.",
                ]);
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
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => 'Registro incompleto',
                    'text' => 'Primero debes completar tu registro de cliente.',
                ]);
        }

        Log::info('INTENTO DE CREAR ESTABLECIMIENTO', [
            'cliente_id' => $cliente->id,
            'datos_recibidos' => $request->except(['_token'])
        ]);

        try {
            $validated = $request->validate([
                'nombre_establecimiento' => 'required|string|min:3|max:255',
                'tipo_establecimiento' => 'required|in:Restaurante,Cafeteria,Food Truck,Panaderia,Bar,Otro',
                'tipo_establecimiento_otro' => 'nullable|string|max:100',
                'lat' => 'required|numeric|between:-90,90',
                'lng' => 'required|numeric|between:-180,180',
                'direccion_completa_establecimiento' => 'required|string|max:500',
                'colonia' => 'required|string|max:100',
                'municipio' => 'required|string|max:100',
                'estado' => 'required|string|max:100',
                'codigo_postal' => 'required|string|size:5|regex:/^[0-9]{5}$/',
                'telefono_establecimiento' => 'required|string|min:10|max:20|regex:/^[0-9]+$/',
                'correo_establecimiento' => 'required|email|max:255',
                'rfc_establecimiento' => 'nullable|string|size:13|regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/',
                'razon_social_establecimiento' => 'nullable|string|max:255',
                'direccion_fiscal_establecimiento' => 'nullable|string|max:500',
                'facturacion_establecimiento' => 'nullable|boolean',
                'tipos_pago_establecimiento' => 'nullable|array',
                'categoria_id' => 'nullable|exists:categorias,id',
                'horarios' => 'nullable|array',
            ], [
                'nombre_establecimiento.required' => 'El nombre del establecimiento es obligatorio',
                'nombre_establecimiento.min' => 'El nombre debe tener al menos 3 caracteres',
                'tipo_establecimiento.required' => 'Debes seleccionar un tipo de establecimiento',
                'lat.required' => 'Debes seleccionar la ubicacion en el mapa',
                'lng.required' => 'Debes seleccionar la ubicacion en el mapa',
                'direccion_completa_establecimiento.required' => 'La direccion es obligatoria',
                'telefono_establecimiento.required' => 'El telefono es obligatorio',
                'telefono_establecimiento.regex' => 'El telefono solo puede contener numeros',
                'correo_establecimiento.required' => 'El correo es obligatorio',
                'correo_establecimiento.email' => 'Debes ingresar un correo valido',
                'codigo_postal.size' => 'El codigo postal debe tener 5 digitos',
                'codigo_postal.regex' => 'El codigo postal solo puede contener numeros',
                'rfc_establecimiento.size' => 'El RFC debe tener exactamente 13 caracteres',
                'rfc_establecimiento.regex' => 'El formato del RFC no es valido',
            ]);

            Log::info('Validacion exitosa', ['validated' => $validated]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validacion', [
                'errores' => $e->errors()
            ]);
            throw $e;
        }

        $tipoFinal = $validated['tipo_establecimiento'];
        if ($tipoFinal === 'Otro' && !empty($validated['tipo_establecimiento_otro'])) {
            $tipoFinal = $validated['tipo_establecimiento_otro'];
        }

        $datosEstablecimiento = [
            'cliente_id' => $cliente->id,
            'nombre_establecimiento' => $validated['nombre_establecimiento'],
            'tipo_establecimiento' => $tipoFinal,
            'lat' => $validated['lat'],
            'lng' => $validated['lng'],
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
            'horarios_establecimiento' => $validated['horarios'] ?? [],
            'categoria_id' => $validated['categoria_id'] ?? null,
            'verificacion_establecimiento' => false,
            'activo' => true,
            'grado_confianza' => 50,
            'cantidad_reportes' => 0,
            'valoracion_promedio' => 0,
            'total_resenas' => 0,
        ];

        Log::info('Datos preparados para insercion', $datosEstablecimiento);

        try {
            $establecimiento = Establecimientos::create($datosEstablecimiento);
            
            Log::info('ESTABLECIMIENTO CREADO EXITOSAMENTE', [
                'id' => $establecimiento->id,
                'nombre' => $establecimiento->nombre_establecimiento
            ]);

            return redirect()
                ->route('establecimientos.index')
                ->with('swal', [
                    'icon' => 'success',
                    'title' => 'Establecimiento creado',
                    'text' => "'{$establecimiento->nombre_establecimiento}' ha sido registrado exitosamente.",
                    'showConfirmButton' => true,
                    'timer' => 3000
                ]);

        } catch (\Exception $e) {
            Log::error('ERROR AL CREAR ESTABLECIMIENTO', [
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('swal', [
                    'icon' => 'error',
                    'title' => 'Error al guardar',
                    'text' => 'Detalles: ' . $e->getMessage(),
                    'showConfirmButton' => true
                ]);
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
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => 'Registro incompleto',
                    'text' => 'Primero debes completar tu registro de cliente.',
                ]);
        }

        $establecimiento = Establecimientos::where('cliente_id', $cliente->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('establecimientos.show', compact('establecimiento'));
    }

    /**
     * Muestra el formulario de edicion
     */
    public function edit($id)
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('registro.completar')
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => 'Registro incompleto',
                    'text' => 'Primero debes completar tu registro de cliente.',
                ]);
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
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => 'Registro incompleto',
                    'text' => 'Primero debes completar tu registro de cliente.',
                ]);
        }

        $establecimiento = Establecimientos::where('cliente_id', $cliente->id)
            ->where('id', $id)
            ->firstOrFail();

        Log::info('INTENTO DE ACTUALIZAR ESTABLECIMIENTO', [
            'establecimiento_id' => $establecimiento->id,
            'datos_recibidos' => $request->except(['_token', '_method'])
        ]);

        try {
            $validated = $request->validate([
                'nombre_establecimiento' => 'required|string|min:3|max:255',
                'tipo_establecimiento' => 'required|in:Restaurante,Cafeteria,Food Truck,Panaderia,Bar,Otro',
                'tipo_establecimiento_otro' => 'nullable|string|max:100',
                'lat' => 'required|numeric|between:-90,90',
                'lng' => 'required|numeric|between:-180,180',
                'direccion_completa_establecimiento' => 'required|string|max:500',
                'colonia' => 'required|string|max:100',
                'municipio' => 'required|string|max:100',
                'estado' => 'required|string|max:100',
                'codigo_postal' => 'required|string|size:5|regex:/^[0-9]{5}$/',
                'telefono_establecimiento' => 'required|string|min:10|max:20|regex:/^[0-9]+$/',
                'correo_establecimiento' => 'required|email|max:255',
                'rfc_establecimiento' => 'nullable|string|size:13|regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/',
                'razon_social_establecimiento' => 'nullable|string|max:255',
                'direccion_fiscal_establecimiento' => 'nullable|string|max:500',
                'facturacion_establecimiento' => 'nullable|boolean',
                'tipos_pago_establecimiento' => 'nullable|array',
                'categoria_id' => 'nullable|exists:categorias,id',
                'horarios' => 'nullable|array',
            ]);

            $tipoFinal = $validated['tipo_establecimiento'];
            if ($tipoFinal === 'Otro' && !empty($validated['tipo_establecimiento_otro'])) {
                $tipoFinal = $validated['tipo_establecimiento_otro'];
            }

            $establecimiento->update([
                'nombre_establecimiento' => $validated['nombre_establecimiento'],
                'tipo_establecimiento' => $tipoFinal,
                'lat' => $validated['lat'],
                'lng' => $validated['lng'],
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
                'horarios_establecimiento' => $validated['horarios'] ?? [],
                'categoria_id' => $validated['categoria_id'] ?? null,
            ]);

            Log::info('ESTABLECIMIENTO ACTUALIZADO EXITOSAMENTE', [
                'id' => $establecimiento->id
            ]);

            return redirect()
                ->route('establecimientos.index')
                ->with('swal', [
                    'icon' => 'success',
                    'title' => 'Establecimiento actualizado',
                    'text' => 'Los cambios se han guardado exitosamente.',
                    'showConfirmButton' => true,
                    'timer' => 3000
                ]);

        } catch (\Exception $e) {
            Log::error('ERROR AL ACTUALIZAR ESTABLECIMIENTO', [
                'establecimiento_id' => $establecimiento->id,
                'mensaje' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('swal', [
                    'icon' => 'error',
                    'title' => 'Error al actualizar',
                    'text' => 'Hubo un error al actualizar el establecimiento. Por favor intenta de nuevo.',
                    'showConfirmButton' => true
                ]);
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
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => 'Registro incompleto',
                    'text' => 'Primero debes completar tu registro de cliente.',
                ]);
        }

        $establecimiento = Establecimientos::where('cliente_id', $cliente->id)
            ->where('id', $id)
            ->firstOrFail();

        try {
            $nombreEstablecimiento = $establecimiento->nombre_establecimiento;
            $establecimiento->delete();

            Log::info('ESTABLECIMIENTO ELIMINADO', [
                'id' => $id,
                'nombre' => $nombreEstablecimiento
            ]);

            return redirect()
                ->route('establecimientos.index')
                ->with('swal', [
                    'icon' => 'success',
                    'title' => 'Establecimiento eliminado',
                    'text' => "'{$nombreEstablecimiento}' ha sido eliminado exitosamente.",
                    'showConfirmButton' => true,
                    'timer' => 3000
                ]);

        } catch (\Exception $e) {
            Log::error('ERROR AL ELIMINAR ESTABLECIMIENTO', [
                'establecimiento_id' => $id,
                'mensaje' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('swal', [
                    'icon' => 'error',
                    'title' => 'Error al eliminar',
                    'text' => 'Hubo un error al eliminar el establecimiento. Por favor intenta de nuevo.',
                    'showConfirmButton' => true
                ]);
        }
    }
}