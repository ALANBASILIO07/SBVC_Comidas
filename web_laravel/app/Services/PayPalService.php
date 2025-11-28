<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Servicio para integración con PayPal
 *
 * Gestiona la autenticación y operaciones con la API de PayPal
 * para crear órdenes, capturar pagos y consultar transacciones.
 */
class PayPalService
{
    protected string $base;
    protected string $clientId;
    protected string $secret;
    protected string $currency;

    public function __construct()
    {
        $isSandbox = config('paypal.mode') === 'sandbox';
        $this->base = $isSandbox
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';

        $this->clientId = config('paypal.client_id');
        $this->secret   = config('paypal.client_secret');
        $this->currency = config('paypal.currency', 'MXN');
    }

    /**
     * Obtiene el token de acceso de PayPal
     *
     * @return array [status, body]
     */
    protected function getAccessToken(): array
    {
        try {
            $resp = Http::asForm()
                ->withBasicAuth($this->clientId, $this->secret)
                ->post("{$this->base}/v1/oauth2/token", [
                    'grant_type' => 'client_credentials'
                ]);

            if (!$resp->successful()) {
                Log::error('PayPal token error', [
                    'status' => $resp->status(),
                    'response' => $resp->json()
                ]);
            }

            return [$resp->status(), $resp->json()];
        } catch (\Exception $e) {
            Log::error('PayPal token exception', [
                'error' => $e->getMessage()
            ]);

            return [500, ['error' => $e->getMessage()]];
        }
    }

    /**
     * Crea una orden de pago en PayPal
     *
     * @param float $amount Monto a cobrar
     * @param string $reference Referencia única de la transacción
     * @return array [status, body]
     */
    public function createOrder(float $amount, string $reference): array
    {
        [$statusToken, $tokenBody] = $this->getAccessToken();

        if ($statusToken !== 200 || empty($tokenBody['access_token'])) {
            return ['status' => $statusToken, 'body' => $tokenBody];
        }

        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => $reference,
                'amount' => [
                    'currency_code' => $this->currency,
                    'value' => number_format($amount, 2, '.', '')
                ]
            ]]
        ];

        try {
            $resp = Http::withToken($tokenBody['access_token'])
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Prefer' => 'return=representation'
                ])
                ->post("{$this->base}/v2/checkout/orders", $payload);

            if (!$resp->successful()) {
                Log::error('PayPal createOrder failed', [
                    'status' => $resp->status(),
                    'debug_id' => $resp->json()['debug_id'] ?? null,
                    'response' => $resp->json()
                ]);
            }

            return ['status' => $resp->status(), 'body' => $resp->json()];
        } catch (\Exception $e) {
            Log::error('PayPal createOrder exception', [
                'error' => $e->getMessage()
            ]);

            return ['status' => 500, 'body' => ['error' => $e->getMessage()]];
        }
    }

    /**
     * Captura el pago de una orden aprobada
     *
     * @param string $orderId ID de la orden de PayPal
     * @return array [status, body]
     */
    public function captureOrder(string $orderId): array
    {
        [$statusToken, $tokenBody] = $this->getAccessToken();

        if ($statusToken !== 200 || empty($tokenBody['access_token'])) {
            return ['status' => $statusToken, 'body' => $tokenBody];
        }

        try {
            $ch = curl_init("{$this->base}/v2/checkout/orders/{$orderId}/capture");

            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer {$tokenBody['access_token']}",
                    "Content-Type: application/json",
                    "Accept: application/json",
                    "Prefer: return=representation",
                    "Content-Length: 0"
                ],
                CURLOPT_POSTFIELDS => ''
            ]);

            $response = curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                Log::error('PayPal cURL error', [
                    'orderId' => $orderId,
                    'error' => $curlError
                ]);

                return ['status' => 500, 'body' => ['error' => 'Connection error']];
            }

            $responseBody = json_decode($response, true) ?: [];

            if ($statusCode !== 201) {
                Log::error('PayPal capture failed', [
                    'orderId' => $orderId,
                    'status' => $statusCode,
                    'debug_id' => $responseBody['debug_id'] ?? null,
                    'response' => $responseBody
                ]);
            }

            return ['status' => $statusCode, 'body' => $responseBody];
        } catch (\Exception $e) {
            Log::error('PayPal capture exception', [
                'orderId' => $orderId,
                'error' => $e->getMessage()
            ]);

            return ['status' => 500, 'body' => ['error' => 'Internal error']];
        }
    }

    /**
     * Obtiene los detalles de una orden
     *
     * @param string $orderId ID de la orden de PayPal
     * @return array [status, body]
     */
    public function getOrder(string $orderId): array
    {
        [$statusToken, $tokenBody] = $this->getAccessToken();

        if ($statusToken !== 200 || empty($tokenBody['access_token'])) {
            return ['status' => $statusToken, 'body' => $tokenBody];
        }

        try {
            $resp = Http::withToken($tokenBody['access_token'])
                ->withHeaders(['Accept' => 'application/json'])
                ->get("{$this->base}/v2/checkout/orders/{$orderId}");

            if (!$resp->successful()) {
                Log::error('PayPal getOrder failed', [
                    'orderId' => $orderId,
                    'status' => $resp->status(),
                    'response' => $resp->json()
                ]);
            }

            return ['status' => $resp->status(), 'body' => $resp->json()];
        } catch (\Exception $e) {
            Log::error('PayPal getOrder exception', [
                'orderId' => $orderId,
                'error' => $e->getMessage()
            ]);

            return ['status' => 500, 'body' => ['error' => 'Internal error']];
        }
    }
}
