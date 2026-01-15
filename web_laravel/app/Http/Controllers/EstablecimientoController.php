<?php
/**
 * Nombre del archivo        : EstablecimientoController.php
 * Descripción               : Controlador de establecimientos del cliente
 * Fecha de creación         : 06/01/2026
 * Elaboró                   : Alan Osvaldo Basilio Delgado
 * Fecha de liberación       : 06/01/2026
 * Autorizó                  : Maileth Patiño Ensastegui
 * Versión                   : 1.2
 * Fecha de mantenimiento    : 20/01/2026
 * Tipo de mantenimiento     : Seguridad / UX / Validación
 * Descripción del mantenimiento: Ajustes en validación de tipos (coincidentes con seeder),
 *                                verificación de correspondencia categoría->tipo,
 *                                y coherencia en la señalización de SweetAlert vía sesión.
 *                                Uso consistente de alertas con botón negro y alertas de éxito sin botón.
 * Responsable               : Alan Osvaldo Basilio Delgado
 * Revisor                   : Maileth Patiño Ensastegui
 */

namespace App\Http\Controllers;

use App\Models\Establecimientos;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EstablecimientoController extends Controller
{
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
                    'confirmButtonColor' => '#000000',
                    'draggable' => true,
                ]);
        }

        $categorias = Categoria::all();

        return view('establecimientos.create', compact('categorias'));
    }

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
                'tipo_establecimiento' => 'required|in:Restaurante,Cafetería,Food Truck,Panadería,Bar,Otro',
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

        if (!empty($validated['categoria_id'])) {
            $categoria = Categoria::find($validated['categoria_id']);
            if (!$categoria) {
                return redirect()->back()->withInput()->with('swal', [
                    'icon' => 'error',
                    'title' => 'Categoría inválida',
                    'text' => 'La categoría seleccionada no existe.',
                    'confirmButtonText' => 'Entendido',
                    'confirmButtonColor' => '#000000',
                ]);
            }

            if ($validated['tipo_establecimiento'] !== 'Otro' && $categoria->tipo_establecimiento !== $validated['tipo_establecimiento']) {
                return redirect()->back()->withInput()->with('swal', [
                    'icon' => 'error',
                    'title' => 'Categoría no permitida',
                    'text' => 'La categoría seleccionada no corresponde con el tipo de establecimiento elegido.',
                    'confirmButtonText' => 'Entendido',
                    'confirmButtonColor' => '#000000',
                ]);
            }
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

            // ---------------------------
            // RESPUESTA FINAL: REDIRECCIÓN AL INDEX CON SWEETALERT EN SESIÓN
            // ---------------------------
            $cfg = [
                'position' => 'top-end',
                'icon' => 'success',
                // Mantengo el título solicitado originalmente
                'title' => 'Establecimiento creado',
                'text' => "{$establecimiento->nombre_establecimiento} ha sido registrado exitosamente.",
                'showConfirmButton' => false,
                'timer' => 1500,
                'confirmButtonColor' => '#000000'
            ];

            Log::info('Preparando redirect()->route(\'establecimientos.index\') con swal', $cfg);

            return redirect()->route('establecimientos.index')->with('swal', $cfg);

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
                    'confirmButtonColor' => '#000000',
                ]);
        }
    }

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
                'tipo_establecimiento' => 'required|in:Restaurante,Cafetería,Food Truck,Panadería,Bar,Otro',
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

            // Verificar correspondencia categoría->tipo como en store()
            if (!empty($validated['categoria_id'])) {
                $categoria = Categoria::find($validated['categoria_id']);
                if ($validated['tipo_establecimiento'] !== 'Otro' && $categoria && $categoria->tipo_establecimiento !== $validated['tipo_establecimiento']) {
                    return redirect()->back()->withInput()->with('swal', [
                        'icon' => 'error',
                        'title' => 'Categoría no permitida',
                        'text' => 'La categoría seleccionada no corresponde con el tipo de establecimiento elegido.',
                        'confirmButtonText' => 'Entendido',
                        'confirmButtonColor' => '#000000',
                    ]);
                }
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
                    'position' => 'top-end',
                    'icon' => 'success',
                    'title' => 'Establecimiento actualizado',
                    'text' => 'Los cambios se han guardado exitosamente.',
                    'showConfirmButton' => false,
                    'timer' => 1500,
                    'confirmButtonColor' => '#000000'
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
                    'confirmButtonColor' => '#000000',
                ]);
        }
    }

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
                    'position' => 'top-end',
                    'icon' => 'success',
                    'title' => 'Establecimiento eliminado',
                    'text' => "'{$nombreEstablecimiento}' ha sido eliminado exitosamente.",
                    'showConfirmButton' => false,
                    'timer' => 1500,
                    'confirmButtonColor' => '#000000'
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
                    'confirmButtonColor' => '#000000',
                ]);
        }
    }
}