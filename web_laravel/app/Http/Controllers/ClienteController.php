<?php
/**
 * Nombre del archivo        : ClienteController.php
 * Descripción               : Controlador para manejar la creación y visualización del cliente,
 *                             además de permitir la actualización rápida de plan desde la vista.
 * Fecha de creación         : 06/01/2026
 * Elaboró                   : Alan Osvaldo Basilio Delgado
 * Fecha de liberación       : 06/01/2026
 * Autorizó                  : Maileth Patiño Ensastegui
 * Versión                   : 1.4
 * Fecha de mantenimiento    : 12/01/2026
 * Folio de mantenimiento    :
 * Tipo de mantenimiento     : UX / Corrección manejo plan/suscripción
 * Descripción del mantenimiento: Guard SPA para rutas protegidas y diferenciar plan asignado vs plan activo.
 * Responsable               : Alan Osvaldo Basilio Delgado
 * Revisor                   : Maileth Patiño Ensastegui
 */

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    /**
     * Muestra el formulario de registro de cliente
     */
    public function create()
    {
        // Verificar si el usuario ya tiene un cliente registrado
        $clienteExistente = Cliente::where('user_id', Auth::id())->first();
        
        if ($clienteExistente) {
            return redirect()->route('dashboard')
                ->with('info', 'Ya has completado tu registro de cliente.');
        }

        return view('clientes.complete_profile');
    }

    /**
     * Guarda el nuevo cliente en la base de datos
     *
     * Nota: Al crear el cliente asignamos un plan por defecto "estandar" (si no se seleccionó uno),
     * pero NO activamos la suscripción (suscripcion_activa = false) hasta que se confirme pago.
     */
    public function store(Request $request)
    {
        // Verificar que el usuario NO tenga ya un cliente
        $clienteExistente = Cliente::where('user_id', Auth::id())->first();
        
        if ($clienteExistente) {
            return redirect()->route('dashboard')
                ->with('warning', 'Ya tienes un registro de cliente completado.');
        }

        // Validar los datos del formulario con reglas estrictas
        $validated = $request->validate([
            'nombre_titular' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\'\-]+$/'
            ],
            'telefono' => [
                'required',
                'string',
                'min:10',
                'max:20',
                'regex:/^[0-9]+$/'
            ],
            'rfc_titular' => [
                'nullable',
                'string',
                'size:13',
                'regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/'
            ],
            'razon_social_titular' => 'nullable|string|max:255',
            'plan' => 'nullable|in:basico,estandar,premium' // aceptar plan desde la vista (si eligió)
        ], [
            'nombre_titular.required' => 'El nombre completo es obligatorio',
            'nombre_titular.regex' => 'El nombre solo puede contener letras, espacios, guiones o apóstrofes',
            'nombre_titular.min' => 'El nombre debe tener al menos 3 caracteres',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.regex' => 'El teléfono solo puede contener números',
            'telefono.min' => 'El teléfono debe tener al menos 10 dígitos',
            'telefono.max' => 'El teléfono no puede exceder 20 dígitos',
            'rfc_titular.size' => 'El RFC debe tener exactamente 13 caracteres',
            'rfc_titular.regex' => 'El formato del RFC no es válido (Ej: XAXX010101000)',
            'plan.in' => 'Plan inválido'
        ]);

        // Si el usuario seleccionó un plan explícitamente lo usamos, si no dejamos 'estandar' como asignado
        $planSeleccionado = $validated['plan'] ?? null;
        $planParaGuardar = $planSeleccionado ?: 'estandar';

        \Log::info('Intentando crear cliente con datos:', [
            'user_id' => Auth::id(),
            'nombre_titular' => $validated['nombre_titular'],
            'email_contacto' => Auth::user()->email,
            'telefono' => $validated['telefono'],
            'plan' => $planParaGuardar,
            'rfc_titular' => $validated['rfc_titular'] ?? null,
            'razon_social_titular' => $validated['razon_social_titular'] ?? null,
        ]);

        // Crear el cliente sin activar suscripción; la activación ocurre una vez que el pago se confirme
        try {
            $cliente = Cliente::create([
                'user_id' => Auth::id(),
                'nombre_titular' => $validated['nombre_titular'],
                'email_contacto' => Auth::user()->email,
                'telefono' => $validated['telefono'],
                'plan' => $planParaGuardar,
                'fecha_inicio_suscripcion' => null,
                'fecha_fin_suscripcion' => null,
                'suscripcion_activa' => false, // NO activa hasta pago
                'rfc_titular' => $validated['rfc_titular'] ?? null,
                'razon_social_titular' => $validated['razon_social_titular'] ?? null,
            ]);

            \Log::info('Cliente creado exitosamente con ID: ' . $cliente->id);

            return redirect()->route('dashboard')
                ->with('success', '¡Registro completado exitosamente! Ahora puedes comenzar a usar la plataforma.');

        } catch (\Exception $e) {
            \Log::error('Error al crear cliente:', [
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Hubo un error al guardar tu información. Error: ' . $e->getMessage());
        }
    }

    /**
     * Cambia/actualiza el plan del cliente (puede ser llamado vía AJAX o form POST)
     *
     * Importante: actualizar el plan no activa la suscripción. La activación debe realizarse
     * a través del flujo de pago (PayPalController::capture) para garantizar coherencia.
     */
    public function changePlan(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:basico,estandar,premium'
        ], [
            'plan.required' => 'Debes seleccionar un plan',
            'plan.in' => 'Plan inválido'
        ]);

        $plan = $request->input('plan');

        $cliente = Cliente::where('user_id', Auth::id())->first();

        if (!$cliente) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Primero debes completar tu registro de cliente.'
                ], 422);
            }

            return redirect()->route('registro.completar')
                ->with('warning', 'Primero debes completar tu registro de cliente.');
        }

        try {
            // Guardar el plan seleccionado, pero NO activar la suscripción
            $cliente->plan = $plan;
            $cliente->fecha_inicio_suscripcion = null;
            $cliente->fecha_fin_suscripcion = null;
            $cliente->suscripcion_activa = false; // debe activarse por pago
            $cliente->save();

            \Log::info('Plan actualizado (pendiente de pago)', [
                'user_id' => Auth::id(),
                'plan' => $plan
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Plan guardado (pendiente de pago)',
                    'plan' => $plan
                ]);
            }

            return redirect()->back()->with('success', 'Plan seleccionado (pendiente de pago): ' . ucfirst($plan));

        } catch (\Exception $e) {
            \Log::error('Error al actualizar plan:', [
                'mensaje' => $e->getMessage()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error al actualizar el plan'
                ], 500);
            }

            return redirect()->back()->with('error', 'Error al actualizar el plan: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el perfil del cliente
     */
    public function show()
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('clientes.create')
                ->with('warning', 'Primero debes completar tu registro.');
        }

        return view('clientes.show', compact('cliente'));
    }
}