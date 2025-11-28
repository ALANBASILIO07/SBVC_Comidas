<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Services\PayPalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Controlador para manejar pagos con PayPal
 *
 * Gestiona la creación y captura de órdenes de pago
 * para actualización de planes de clientes
 */
class PayPalController extends Controller
{
    public function __construct(private PayPalService $paypal)
    {
    }

    /**
     * Crea una orden de pago en PayPal
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->validate([
            'plan' => [
                'required',
                'string',
                'in:basico,estandar,premium'
            ],
        ]);

        $user = Auth::user();
        $cliente = $user->cliente;

        if (!$cliente) {
            return response()->json([
                'message' => 'No tienes un perfil de cliente activo',
            ], 422);
        }

        // Precios de los planes
        $prices = [
            'basico' => 0.00,      // Gratis
            'estandar' => 299.00,  // $299 MXN
            'premium' => 599.00    // $599 MXN
        ];

        $planName = $data['plan'];
        $amount = $prices[$planName];

        // Si es plan básico, no requiere pago
        if ($amount == 0) {
            return response()->json([
                'message' => 'El plan básico es gratuito',
            ], 422);
        }

        // MODO DEMO: Simular creación de orden
        if (config('paypal.mode') === 'demo') {
            DB::transaction(function () use ($cliente, $planName) {
                $cliente->plan = $planName;
                $cliente->save();
            });

            Log::info('DEMO: Plan activated', [
                'cliente_id' => $cliente->id,
                'plan' => $planName,
                'mode' => 'demo'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Plan actualizado exitosamente (DEMO)',
                'plan' => $planName
            ]);
        }

        $reference = 'plan-' . $planName . '-cliente-' . $cliente->id . '-' . time();

        $order = $this->paypal->createOrder($amount, $reference);

        if (($order['status'] ?? 500) !== 201) {
            Log::error('PayPal CREATE error', [
                'plan' => $planName,
                'cliente_id' => $cliente->id,
                'status' => $order['status'] ?? 500,
                'response' => $order['body'] ?? null
            ]);

            return response()->json([
                'message' => 'Error al crear la orden en PayPal',
                'details' => $order['body']['message'] ?? 'Error desconocido'
            ], 422);
        }

        return response()->json([
            'id' => $order['body']['id']
        ]);
    }

    /**
     * Captura el pago de una orden aprobada
     *
     * @param Request $request
     * @param string $orderId
     * @return JsonResponse
     */
    public function capture(Request $request, string $orderId): JsonResponse
    {
        // Obtener información de la orden
        $orderInfo = $this->paypal->getOrder($orderId);

        if (($orderInfo['status'] ?? 500) !== 200) {
            Log::error('PayPal getOrder failed', [
                'orderId' => $orderId,
                'status' => $orderInfo['status'] ?? 500,
                'response' => $orderInfo['body'] ?? null
            ]);

            return response()->json([
                'message' => 'No se pudo verificar el estado de la orden',
                'debug_id' => $orderInfo['body']['debug_id'] ?? null
            ], 422);
        }

        $orderStatus = $orderInfo['body']['status'] ?? null;
        $orderBody = $orderInfo['body'];

        if ($orderStatus !== 'APPROVED') {
            Log::warning('Order not in APPROVED state', [
                'orderId' => $orderId,
                'status' => $orderStatus,
                'order' => $orderBody
            ]);

            $message = match ($orderStatus) {
                'COMPLETED' => 'Esta orden ya fue procesada anteriormente',
                'CREATED' => 'El pago aún no ha sido aprobado por el usuario',
                'VOIDED' => 'Esta orden fue cancelada',
                'SAVED' => 'La orden está guardada pero no aprobada',
                default => "Estado de orden inválido: {$orderStatus}"
            };

            return response()->json([
                'message' => $message,
                'status' => $orderStatus,
                'order_id' => $orderId
            ], 422);
        }

        // Capturar el pago
        $capture = $this->paypal->captureOrder($orderId);

        if (($capture['status'] ?? 500) !== 201) {
            $captureBody = $capture['body'] ?? [];

            Log::error('PayPal CAPTURE failed', [
                'orderId' => $orderId,
                'status' => $capture['status'] ?? 500,
                'debug_id' => $captureBody['debug_id'] ?? null,
                'name' => $captureBody['name'] ?? null,
                'message' => $captureBody['message'] ?? null,
                'details' => $captureBody['details'] ?? null,
                'full_response' => $captureBody
            ]);

            return response()->json([
                'message' => $captureBody['message'] ?? 'Error al capturar el pago',
                'debug_id' => $captureBody['debug_id'] ?? null,
                'details' => $captureBody['details'] ?? null
            ], 422);
        }

        $captureBody = $capture['body'] ?? [];
        $captureStatus = $captureBody['status'] ?? null;

        if ($captureStatus !== 'COMPLETED') {
            Log::warning('Capture not completed', [
                'orderId' => $orderId,
                'status' => $captureStatus,
                'response' => $captureBody
            ]);

            return response()->json([
                'message' => 'La captura del pago no se completó',
                'status' => $captureStatus
            ], 422);
        }

        // Extraer información de la referencia
        $reference = data_get($captureBody, 'purchase_units.0.reference_id', '');

        if (!preg_match('/^plan-(\w+)-cliente-(\d+)-(\d+)$/', $reference, $matches)) {
            Log::error('Invalid reference in capture', [
                'orderId' => $orderId,
                'reference' => $reference,
                'capture' => $captureBody
            ]);

            return response()->json([
                'message' => 'Referencia de plan inválida'
            ], 422);
        }

        [, $planName, $clienteIdFromRef, $timestamp] = $matches;

        $user = Auth::user();
        $cliente = $user->cliente;

        if (!$cliente || $cliente->id != $clienteIdFromRef) {
            Log::error('Cliente mismatch in capture', [
                'orderId' => $orderId,
                'authenticated_cliente' => $cliente->id ?? null,
                'reference_cliente' => $clienteIdFromRef
            ]);

            return response()->json([
                'message' => 'Usuario no autorizado para esta transacción'
            ], 403);
        }

        // Actualizar el plan del cliente
        try {
            DB::transaction(function () use ($cliente, $planName) {
                $cliente->plan = $planName;
                $cliente->save();
            });

            Log::info('Plan activated successfully', [
                'orderId' => $orderId,
                'cliente_id' => $cliente->id,
                'plan' => $planName,
                'user_id' => $user->id
            ]);

            return response()->json([
                'message' => 'Pago completado exitosamente',
                'plan' => $planName,
                'redirect' => route('dashboard')
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving plan to cliente', [
                'orderId' => $orderId,
                'cliente_id' => $cliente->id,
                'plan' => $planName,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Error al activar el plan. Contacta soporte.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
