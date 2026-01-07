<?php

/**
 * Nombre del archivo        : EstablecimientoController.php
 * Descripción               : Controlador de establecimientos del cliente
 * Fecha de creación         : 06/01/2026
 * Elaboró                   : Alan Osvaldo Basilio Delgado
 * Fecha de liberación       : 06/01/2026
 * Autorizó                  : Maileth Patiño Ensastegui
 * Version                   : 1.0
 * Fecha de mantenimiento    : 06/01/2026
 * Folio de mantenimiento    :
 * Tipo de mantenimiento     : Seguridad / UX
 * Descripción del mantenimiento: Implementación de validación de cliente y plan con SweetAlert
 * Responsable               : Alan Osvaldo Basilio Delgado
 * Revisor                   : Maileth Patiño Ensastegui
 */

namespace App\Http\Controllers;

use App\Models\Establecimientos;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Helpers\SweetAlertHelper;

class EstablecimientoController extends Controller
{
    /**
     * Muestra la lista de establecimientos
     */
    public function index()
    {
        $user = Auth::user();
        $cliente = $user->cliente;

        if (!$cliente) {
            return SweetAlertHelper::registroIncompleto('registro.completar');
        }

        if (empty($cliente->plan) || $cliente->plan === 'sin_plan') {
            return SweetAlertHelper::planRequerido('subscripcion.index');
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
        $user = Auth::user();
        $cliente = $user->cliente;

        if (!$cliente) {
            return SweetAlertHelper::registroIncompleto('registro.completar');
        }

        if (empty($cliente->plan) || $cliente->plan === 'sin_plan') {
            return SweetAlertHelper::planRequerido('subscripcion.index');
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
                    'title' => 'Límite alcanzado',
                    'text' => "Has alcanzado el límite de establecimientos para tu plan {$cliente->plan}. Actualiza tu plan para agregar más.",
                    'confirmButtonText' => 'Entendido',
                    'confirmButtonColor' => '#F7941D',
                    'draggable' => true,
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
        $user = Auth::user();
        $cliente = $user->cliente;

        if (!$cliente) {
            return SweetAlertHelper::registroIncompleto('registro.completar');
        }

        if (empty($cliente->plan) || $cliente->plan === 'sin_plan') {
            return SweetAlertHelper::planRequerido('subscripcion.index');
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
                'lat.required' => 'Debes seleccionar la ubicación en el mapa',
                'lng.required' => 'Debes seleccionar la ubicación en el mapa',
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

            Log::info('Validación exitosa', ['validated' => $validated]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación', [
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

        Log::info('Datos preparados para inserción', $datosEstablecimiento);

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
                    'confirmButtonText' => 'Continuar',
                    'confirmButtonColor' => '#F7941D',
                    'draggable' => true,
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
                    'confirmButtonText' => 'Entendido',
                    'confirmButtonColor' => '#F7941D',
                    'draggable' => true,
                ]);
        }
    }

    /**
     * Muestra el detalle de un establecimiento
     */
    public function show($id)
    {
        $user = Auth::user();
        $cliente = $user->cliente;

        if (!$cliente) {
            return SweetAlertHelper::registroIncompleto('registro.completar');
        }

        if (empty($cliente->plan) || $cliente->plan === 'sin_plan') {
            return SweetAlertHelper::planRequerido('subscripcion.index');
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
        $user = Auth::user();
        $cliente = $user->cliente;

        if (!$cliente) {
            return SweetAlertHelper::registroIncompleto('registro.completar');
        }

        if (empty($cliente->plan) || $cliente->plan === 'sin_plan') {
            return SweetAlertHelper::planRequerido('subscripcion.index');
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
        $user = Auth::user();
        $cliente = $user->cliente;

        if (!$cliente) {
            return SweetAlertHelper::registroIncompleto('registro.completar');
        }

        if (empty($cliente->plan) || $cliente->plan === 'sin_plan') {
            return SweetAlertHelper::planRequerido('subscripcion.index');
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
                    'confirmButtonText' => 'Continuar',
                    'confirmButtonColor' => '#F7941D',
                    'draggable' => true,
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
                    'confirmButtonText' => 'Entendido',
                    'confirmButtonColor' => '#F7941D',
                    'draggable' => true,
                ]);
        }
    }

    /**
     * Elimina un establecimiento
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $cliente = $user->cliente;

        if (!$cliente) {
            return SweetAlertHelper::registroIncompleto('registro.completar');
        }

        if (empty($cliente->plan) || $cliente->plan === 'sin_plan') {
            return SweetAlertHelper::planRequerido('subscripcion.index');
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
                    'confirmButtonText' => 'Continuar',
                    'confirmButtonColor' => '#F7941D',
                    'draggable' => true,
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
                    'confirmButtonText' => 'Entendido',
                    'confirmButtonColor' => '#F7941D',
                    'draggable' => true,
                ]);
        }
    }
}