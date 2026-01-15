<?php
/**
 * Nombre del archivo        : ClienteController.php
 * Ruta                      : app/Http/Controllers/ClienteController.php
 * Descripción               : Controlador para manejar la creación, actualización y gestión
 * del cliente, incluyendo la lógica completa de planes y suscripciones.
 * Responde en español y devuelve JSON para peticiones AJAX (pensado para uso
 * con SweetAlert en el frontend). Implementa validaciones, transiciones
 * de plan (upgrade inmediato con pago, downgrade programado), simulación de pagos
 * en modo demo y comprobaciones de límites de recursos.
 * Fecha de creación         : 06/01/2026
 * Elaboró                   : Alan Osvaldo Basilio Delgado
 * Fecha de liberación       : 06/01/2026
 * Autorizó                  : Maileth Patiño Ensastegui
 * Versión                   : 2.3
 * Fecha de mantenimiento    : 19/01/2026
 * Tipo de mantenimiento     : Corrección Error SQL 500 / Integridad de Datos
 * Descripción del mantenimiento:
 * - FIX CRITICO: Se agrega el campo 'email_contacto' al método store() para evitar el error
 * Integrity constraint violation (NOT NULL) en la base de datos.
 * - Aseguramiento de respuestas JSON consistentes.
 * - Manejo de transacciones para la activación inmediata del plan.
 * Responsable               : Alan Osvaldo Basilio Delgado
 * Revisor                   : Maileth Patiño Ensastegui
 */

namespace App\Http\Controllers;

use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    /**
     * Guarda o actualiza la información del cliente y su plan (registro o edición).
     * Si la petición es AJAX (fetch), responde JSON en español para integrarse con SweetAlert.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'nombre_titular' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u'],
            'telefono' => ['required', 'digits:10'],
            'rfc_titular' => ['nullable', 'string', 'size:13', 'regex:/^[A-ZÑ&0-9]{13}$/'],
            'razon_social_titular' => ['nullable', 'string', 'max:255'],
            'plan' => ['nullable', 'string', 'in:basico,premium'],
        ];

        $messages = [
            'nombre_titular.regex' => 'El nombre solo puede contener letras y espacios.',
            'telefono.digits' => 'El teléfono debe tener exactamente 10 dígitos.',
            'rfc_titular.size' => 'El RFC debe tener exactamente 13 caracteres.',
            'rfc_titular.regex' => 'El RFC debe contener solo letras mayúsculas, números y &.',
            'plan.in' => 'El plan seleccionado no es válido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // Si la petición es AJAX, devolver JSON con errores
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Errores de validación.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Se usa updateOrCreate para guardar o actualizar el perfil del cliente.
            // FIX: Se agregó 'email_contacto' => $user->email para cumplir con la restricción NOT NULL de la DB.
            $cliente = Cliente::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nombre_titular' => $request->input('nombre_titular'),
                    'email_contacto' => $user->email, // <--- CAMBIO CRITICO AQUÍ
                    'telefono' => $request->input('telefono'),
                    'rfc_titular' => $request->input('rfc_titular'),
                    'razon_social_titular' => $request->input('razon_social_titular'),
                ]
            );

            // Si se envió plan y el cliente no tiene suscripción vigente, asignarlo inmediatamente
            // Esto asume que el pago (simulado) fue exitoso en el frontend
            $planSolicitado = $request->input('plan');
            if ($planSolicitado && !$cliente->suscripcionVigente()) {
                $ahora = Carbon::now();
                $cliente->update([
                    'plan' => $planSolicitado,
                    'suscripcion_activa' => true,
                    'fecha_inicio_suscripcion' => $ahora,
                    'fecha_fin_suscripcion' => $ahora->copy()->addMonth(),
                    'plan_proximo_vencimiento' => null,
                ]);
            }

            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Perfil guardado correctamente.',
                    'cliente' => $cliente,
                ]);
            }

            return redirect()->route('dashboard')->with('success', 'Perfil guardado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('ClienteController@store: error al guardar cliente', [
                'error' => $e->getMessage(),
                'user_id' => $user->id ?? null,
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                // En producción es mejor no enviar $e->getMessage(), pero para debug ayuda.
                // Aquí enviamos un mensaje genérico al usuario pero logueamos el real.
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ocurrió un error al guardar los datos. Intenta nuevamente.',
                    'debug_error' => config('app.debug') ? $e->getMessage() : null // Solo si debug está activo
                ], 500);
            }

            return redirect()->back()->with('error', 'Ocurrió un error al guardar los datos. Intenta nuevamente.');
        }
    }

    /**
     * Cambia el plan del cliente según reglas de negocio.
     * Responde JSON en español para consumo por JavaScript (SweetAlert).
     *
     * Reglas principales implementadas:
     * - Upgrade (basico -> premium): pago completo (o simulación en modo demo), cambio inmediato. Si viene de basico se aplica 50% de descuento el primer mes.
     * - Downgrade (premium -> basico): programado al final del periodo vigente; se informa fecha de cambio y advertencias sobre pérdida de características.
     * - Contratación inicial (sin plan): activación inmediata con fechas.
     * - Cancelación a "sin_plan": sólo si el periodo ya venció.
     *
     * Parámetros adicionales aceptados (opcionales):
     * - simulate_payment (boolean): en modo demo puede forzar la simulación del pago.
     * - amount (numeric): monto enviado por frontend (opcional, validación básica).
     */
    public function changePlan(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Usuario no autenticado.',
            ], 401);
        }

        $cliente = $user->cliente;

        if (!$cliente) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se encontró información del cliente. Completa tu registro.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'plan' => ['required', 'string', 'in:basico,premium,sin_plan'],
            'simulate_payment' => ['sometimes', 'boolean'],
            'amount' => ['sometimes', 'numeric'],
        ], [
            'plan.in' => 'El plan solicitado no es válido.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Errores de validación.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $nuevoPlan = strtolower($request->input('plan'));
        $simulatePayment = (bool) $request->input('simulate_payment', false);
        $amount = $request->input('amount', null);
        $ahora = Carbon::now();

        // Intentar aplicar downgrade programado si corresponde (mantenimiento)
        try {
            $cliente->aplicarDowngradeSiCorresponde();
            $cliente->refresh();
        } catch (\Throwable $e) {
            Log::warning('changePlan: error aplicando downgrade programado', [
                'cliente_id' => $cliente->id,
                'error' => $e->getMessage(),
            ]);
        }

        $planActual = strtolower($cliente->plan ?? 'sin_plan');

        // Obtener conteo de recursos actuales para advertencias (si es posible)
        $resourceCounts = $this->obtenerConteoRecursos($cliente);

        // Si ya tiene el plan y está vigente
        if ($planActual === $nuevoPlan && $cliente->suscripcionVigente()) {
            return response()->json([
                'status' => 'info',
                'message' => "Ya tienes el plan {$nuevoPlan} activo.",
                'plan' => $planActual,
                'fecha_fin' => $cliente->fecha_fin_suscripcion?->format('d/m/Y'),
            ]);
        }

        // Caso: basico -> premium (upgrade inmediato)
        if ($planActual === 'basico' && $nuevoPlan === 'premium') {
            // Precio estándar
            $precioPremium = 399.00;
            // Descuento 50% el primer mes por venir de básico
            $precioConDescuento = round($precioPremium * 0.5, 2);

            // Si no es demo y no se indicó simulate_payment, solicitar iniciar pago real
            $paypalMode = config('paypal.mode', 'demo');

            if ($paypalMode !== 'demo' && !$simulatePayment) {
                return response()->json([
                    'status' => 'requires_payment',
                    'message' => 'Se requiere procesar el pago real para el upgrade a Premium.',
                    'amount' => $precioConDescuento,
                    // frontend debería redirigir al flujo de pago (ej. PayPal)
                ], 402);
            }

            // En demo o simulate_payment asumimos pago aprobado
            DB::beginTransaction();
            try {
                $cliente->plan = 'premium';
                $cliente->suscripcion_activa = true;
                $cliente->fecha_inicio_suscripcion = $ahora;
                $cliente->fecha_fin_suscripcion = $ahora->copy()->addMonth();
                $cliente->plan_proximo_vencimiento = null;
                $cliente->save();

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Upgrade a Premium realizado correctamente. Se aplicó descuento del 50% para el primer mes.',
                    'precio_pagado' => $precioConDescuento,
                    'fecha_inicio' => $cliente->fecha_inicio_suscripcion->format('d/m/Y'),
                    'fecha_fin' => $cliente->fecha_fin_suscripcion->format('d/m/Y'),
                ], 200);
            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error('changePlan: error al aplicar upgrade', [
                    'cliente_id' => $cliente->id,
                    'error' => $e->getMessage(),
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error al procesar el upgrade. Intenta nuevamente más tarde.',
                ], 500);
            }
        }

        // Caso: premium -> basico (downgrade programado)
        if ($planActual === 'premium' && $nuevoPlan === 'basico') {
            // Si no hay fecha de fin, devolver instrucción (raro, pero por seguridad)
            if (!$cliente->fecha_fin_suscripcion) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se puede programar el cambio: la suscripción actual no tiene fecha de vencimiento definida.',
                ], 422);
            }

            // Determinar advertencias (recursos que se perderán)
            $warnings = $this->generarAdvertenciasPorDowngrade($cliente, 'basico', $resourceCounts);

            // Guardar plan_proximo_vencimiento para ejecutar el downgrade cuando venza
            try {
                $cliente->plan_proximo_vencimiento = 'basico';
                $cliente->save();

                return response()->json([
                    'status' => 'scheduled',
                    'message' => 'El cambio a Básico fue programado y se aplicará al finalizar tu periodo vigente.',
                    'change_date' => $cliente->fecha_fin_suscripcion->format('d/m/Y'),
                    'warnings' => $warnings,
                ], 200);
            } catch (\Throwable $e) {
                Log::error('changePlan: error al programar downgrade', [
                    'cliente_id' => $cliente->id,
                    'error' => $e->getMessage(),
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se pudo programar el cambio. Intenta nuevamente.',
                ], 500);
            }
        }

        // Caso: cancelar a "sin_plan" (solo si venció)
        if (in_array($nuevoPlan, ['sin_plan']) && in_array($planActual, ['basico', 'premium'])) {
            if ($cliente->fecha_fin_suscripcion && $cliente->fecha_fin_suscripcion->isPast()) {
                try {
                    $cliente->plan = 'sin_plan';
                    $cliente->suscripcion_activa = false;
                    $cliente->fecha_inicio_suscripcion = null;
                    $cliente->fecha_fin_suscripcion = null;
                    $cliente->plan_proximo_vencimiento = null;
                    $cliente->save();

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Tu plan ha sido dado de baja correctamente. No tienes plan activo.',
                    ], 200);
                } catch (\Throwable $e) {
                    Log::error('changePlan: error al cancelar plan', [
                        'cliente_id' => $cliente->id,
                        'error' => $e->getMessage(),
                    ]);
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Error al cancelar el plan. Intenta nuevamente.',
                    ], 500);
                }
            }

            return response()->json([
                'status' => 'error',
                'message' => 'No es posible dar de baja el plan antes de que venza el periodo actual.',
            ], 422);
        }

        // Caso: contratación inicial o reactivación (sin plan o sin suscripción vigente)
        if (($planActual === 'sin_plan' || !$cliente->suscripcionVigente()) && in_array($nuevoPlan, ['basico', 'premium'])) {
            $precio = $nuevoPlan === 'premium' ? 399.00 : 199.00;
            // En demo o simulatePayment asumimos aprobado
            $paypalMode = config('paypal.mode', 'demo');
            if ($paypalMode !== 'demo' && !$simulatePayment) {
                return response()->json([
                    'status' => 'requires_payment',
                    'message' => 'Se requiere procesar el pago para activar el plan.',
                    'amount' => $precio,
                ], 402);
            }

            DB::beginTransaction();
            try {
                $cliente->plan = $nuevoPlan;
                $cliente->suscripcion_activa = true;
                $cliente->fecha_inicio_suscripcion = $ahora;
                $cliente->fecha_fin_suscripcion = $ahora->copy()->addMonth();
                $cliente->plan_proximo_vencimiento = null;
                $cliente->save();

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => "Plan {$nuevoPlan} activado correctamente.",
                    'fecha_inicio' => $cliente->fecha_inicio_suscripcion->format('d/m/Y'),
                    'fecha_fin' => $cliente->fecha_fin_suscripcion->format('d/m/Y'),
                ], 200);
            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error('changePlan: error al activar plan', [
                    'cliente_id' => $cliente->id,
                    'error' => $e->getMessage(),
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error al activar el plan. Intenta nuevamente.',
                ], 500);
            }
        }

        // Si ninguna regla aplicó, devolver info
        return response()->json([
            'status' => 'info',
            'message' => 'No se realizó ningún cambio en el plan.',
        ], 200);
    }

    /**
     * Intenta obtener cantidad de recursos del cliente (establecimientos, promociones, banners).
     * Si las relaciones no existen o fallan, devuelve null para cada contador.
     *
     * @return array{establecimientos: int|null, promociones: int|null, banners: int|null}
     */
    protected function obtenerConteoRecursos(Cliente $cliente): array
    {
        $counts = [
            'establecimientos' => null,
            'promociones' => null,
            'banners' => null,
        ];

        try {
            if (method_exists($cliente, 'establecimientos')) {
                $counts['establecimientos'] = $cliente->establecimientos()->count();
            }
        } catch (\Throwable $e) {
            Log::warning('obtenerConteoRecursos: no se pudo contar establecimientos', [
                'cliente_id' => $cliente->id,
                'error' => $e->getMessage(),
            ]);
        }

        try {
            if (method_exists($cliente, 'promociones')) {
                $counts['promociones'] = $cliente->promociones()->count();
            }
        } catch (\Throwable $e) {
            Log::warning('obtenerConteoRecursos: no se pudo contar promociones', [
                'cliente_id' => $cliente->id,
                'error' => $e->getMessage(),
            ]);
        }

        try {
            if (method_exists($cliente, 'banners')) {
                $counts['banners'] = $cliente->banners()->count();
            }
        } catch (\Throwable $e) {
            Log::warning('obtenerConteoRecursos: no se pudo contar banners', [
                'cliente_id' => $cliente->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $counts;
    }

    /*
     * Genera advertencias (mensajes) cuando se programa un downgrade según límites del plan destino.
     * Retorna array de strings con advertencias para mostrar en frontend.
     */
    protected function generarAdvertenciasPorDowngrade(Cliente $cliente, string $planDestino, array $resourceCounts = []): array
    {
        $warnings = [];

        $limitesDestino = [
            'basico' => [
                'establecimientos' => 2,
                'promociones' => 10,
                'banners' => 3,
            ],
            'premium' => [
                'establecimientos' => 6,
                'promociones' => 30,
                'banners' => 10,
            ],
        ];

        $destino = $planDestino;
        $limites = $limitesDestino[$destino] ?? null;
        if (!$limites) {
            return $warnings;
        }

        // Comparar con conteos actuales (si disponibles)
        foreach (['establecimientos', 'promociones', 'banners'] as $key) {
            $actual = $resourceCounts[$key] ?? null;
            if ($actual === null) {
                // No se pudo determinar; advertir de forma general
                $warnings[] = "Al degradar a {$destino}, algunos recursos podrían verse limitados o deshabilitados si exceden el límite del plan.";
                break;
            }

            if ($actual > $limites[$key]) {
                $warnings[] = "Actualmente tienes {$actual} {$key} activos; el Plan {$destino} permite máximo {$limites[$key]}. El exceso será deshabilitado o limitado al momento del cambio.";
            }
        }

        if (empty($warnings)) {
            $warnings[] = "No se detectaron recursos que excedan los límites del Plan {$destino}. Aún así se avisará si hay cambios al momento del downgrade.";
        }

        return $warnings;
    }
}