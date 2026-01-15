<?php
/**
 * Nombre del archivo        : PayPalService.php
 * Ruta                      : app/Services/PayPalService.php
 * Descripción               : Servicio para integración con PayPal.
 *                             Gestiona autenticación (cacheada), creación de órdenes,
 *                             captura de pagos y consulta de órdenes.
 *                             Devuelve arrays con 'status' (HTTP code) y 'body' (respuesta asociativa).
 *                             Mensajes de error relevantes están en español para facilitar la integración
 *                             con controladores y frontend (SweetAlert).
 *
 * Fecha de creación         : 06/01/2026
 * Elaboró                   : Alan Osvaldo Basilio Delgado
 * Versión                   : 1.3
 * Fecha de mantenimiento    : 18/01/2026
 * Tipo de mantenimiento     : Mejora/Refactorización
 * Descripción del mantenimiento: Reemplazo de cURL por Http client, cache del token, retries,
 *                                manejo de errores consistente y mensajes en español en cuerpos de error.
 * Responsable               : Alan Osvaldo Basilio Delgado
 * Revisor                   : Maileth Patiño Ensastegui
 */

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Throwable;

class PayPalService
{
    protected string $base;
    protected ?string $clientId;
    protected ?string $secret;
    protected string $currency;
    protected int $retryAttempts;
    protected int $retryDelayMs;

    public function __construct()
    {
        $mode = config('paypal.mode', 'sandbox'); // 'sandbox' o 'live'
        $isSandbox = $mode === 'sandbox' || $mode === 'demo';
        $this->base = $isSandbox
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';

        $this->clientId = config('paypal.client_id');
        $this->secret   = config('paypal.client_secret');
        $this->currency = config('paypal.currency', 'MXN');

        // Configuración de reintentos para llamadas a la API externas
        $this->retryAttempts = (int) config('paypal.retry_attempts', 2);
        $this->retryDelayMs  = (int) config('paypal.retry_delay_ms', 200);
    }

    /**
     * Obtiene (y cachea) el token de acceso de PayPal.
     *
     * Retorna: [statusHttp, bodyArray]
     * En bodyArray en caso de éxito: ['access_token' => '...', 'expires_in' => 32398, 'scope' => '...']
     */
    protected function getAccessToken(): array
    {
        // Validación mínima de configuración
        if (empty($this->clientId) || empty($this->secret)) {
            $msg = 'Credenciales de PayPal no configuradas.';
            Log::error('PayPalService::getAccessToken - ' . $msg);
            return [500, ['error' => $msg]];
        }

        $cacheKey = 'paypal_access_token_' . md5($this->clientId . '|' . $this->base);
        $cached = Cache::get($cacheKey);

        if (!empty($cached['access_token']) && !empty($cached['expires_at']) && now()->lt($cached['expires_at'])) {
            return [200, [
                'access_token' => $cached['access_token'],
                'expires_in' => now()->diffInSeconds($cached['expires_at']),
                'cached' => true
            ]];
        }

        try {
            $resp = Http::asForm()
                ->withBasicAuth($this->clientId, $this->secret)
                ->timeout(10)
                ->post("{$this->base}/v1/oauth2/token", [
                    'grant_type' => 'client_credentials'
                ]);

            $status = $resp->status();
            $body = $resp->successful() ? $resp->json() : $resp->json() ?? ['error' => 'Respuesta inválida de PayPal'];

            if ($status !== 200 || empty($body['access_token'])) {
                Log::error('PayPalService::getAccessToken - Error al obtener token', [
                    'status' => $status,
                    'response' => $body,
                ]);
                $message = $body['error_description'] ?? ($body['error'] ?? 'No se pudo obtener token de PayPal.');
                return [$status, ['error' => "Error al obtener token de PayPal: {$message}", 'raw' => $body]];
            }

            $expiresIn = (int) ($body['expires_in'] ?? 3200);
            // Guardar en cache con margen de seguridad (10 segundos menos)
            $expiresAt = now()->addSeconds(max(30, $expiresIn - 10));
            Cache::put($cacheKey, [
                'access_token' => $body['access_token'],
                'expires_at' => $expiresAt
            ], $expiresIn / 60); // TTL en minutos (aprox)

            return [200, [
                'access_token' => $body['access_token'],
                'expires_in' => $expiresIn,
                'cached' => false
            ]];
        } catch (Throwable $e) {
            Log::error('PayPalService::getAccessToken - Excepción', [
                'error' => $e->getMessage()
            ]);
            return [500, ['error' => 'Error de conexión al obtener token de PayPal.', 'details' => $e->getMessage()]];
        }
    }

    /**
     * Crea una orden de pago en PayPal.
     *
     * @param float $amount
     * @param string $reference
     * @return array ['status' => httpCode, 'body' => array]
     */
    public function createOrder(float $amount, string $reference): array
    {
        [$statusToken, $tokenBody] = $this->getAccessToken();
        if ($statusToken !== 200 || empty($tokenBody['access_token'])) {
            return ['status' => $statusToken, 'body' => $tokenBody];
        }

        // Formato seguro para el monto
        $amountFormatted = number_format(max(0, $amount), 2, '.', '');

        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => $reference,
                'amount' => [
                    'currency_code' => $this->currency,
                    'value' => $amountFormatted
                ]
            ]],
            // Puedes agregar application_context aquí si quieres controlar return/cancel URLs desde backend
        ];

        try {
            $resp = Http::withToken($tokenBody['access_token'])
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Prefer' => 'return=representation',
                ])
                ->timeout(15)
                ->retry($this->retryAttempts, $this->retryDelayMs)
                ->post("{$this->base}/v2/checkout/orders", $payload);

            $status = $resp->status();
            $body = $resp->successful() ? $resp->json() : ($resp->json() ?? ['error' => 'Respuesta inválida de PayPal']);

            if (!$resp->successful()) {
                Log::error('PayPalService::createOrder - fallo', [
                    'status' => $status,
                    'reference' => $reference,
                    'response' => $body
                ]);
                $message = $body['message'] ?? ($body['error'] ?? 'Error al crear orden en PayPal.');
                return ['status' => $status, 'body' => ['error' => "No fue posible crear la orden de pago: {$message}", 'raw' => $body]];
            }

            return ['status' => $status, 'body' => $body];
        } catch (Throwable $e) {
            Log::error('PayPalService::createOrder - excepción', [
                'reference' => $reference,
                'error' => $e->getMessage()
            ]);
            return ['status' => 500, 'body' => ['error' => 'Excepción al crear orden de PayPal.', 'details' => $e->getMessage()]];
        }
    }

    /**
     * Captura el pago de una orden aprobada.
     *
     * @param string $orderId
     * @return array ['status' => httpCode, 'body' => array]
     */
    public function captureOrder(string $orderId): array
    {
        if (empty($orderId)) {
            return ['status' => 422, 'body' => ['error' => 'OrderId inválido.']];
        }

        [$statusToken, $tokenBody] = $this->getAccessToken();
        if ($statusToken !== 200 || empty($tokenBody['access_token'])) {
            return ['status' => $statusToken, 'body' => $tokenBody];
        }

        try {
            $resp = Http::withToken($tokenBody['access_token'])
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Prefer' => 'return=representation',
                ])
                ->timeout(15)
                ->retry($this->retryAttempts, $this->retryDelayMs)
                ->post("{$this->base}/v2/checkout/orders/{$orderId}/capture", []); // body vacío

            $status = $resp->status();
            $body = $resp->successful() ? $resp->json() : ($resp->json() ?? ['error' => 'Respuesta inválida de PayPal']);

            // PayPal responde 201 en capture exitoso
            if ($status !== 201 && !$resp->successful()) {
                Log::error('PayPalService::captureOrder - fallo', [
                    'orderId' => $orderId,
                    'status' => $status,
                    'response' => $body,
                ]);
                $message = $body['message'] ?? ($body['error'] ?? 'No se pudo capturar la orden.');
                return ['status' => $status, 'body' => ['error' => "No fue posible capturar la orden: {$message}", 'raw' => $body]];
            }

            return ['status' => $status, 'body' => $body];
        } catch (Throwable $e) {
            Log::error('PayPalService::captureOrder - excepción', [
                'orderId' => $orderId,
                'error' => $e->getMessage()
            ]);
            return ['status' => 500, 'body' => ['error' => 'Excepción al capturar la orden en PayPal.', 'details' => $e->getMessage()]];
        }
    }

    /**
     * Obtiene los detalles de una orden.
     *
     * @param string $orderId
     * @return array ['status' => httpCode, 'body' => array]
     */
    public function getOrder(string $orderId): array
    {
        if (empty($orderId)) {
            return ['status' => 422, 'body' => ['error' => 'OrderId inválido.']];
        }

        [$statusToken, $tokenBody] = $this->getAccessToken();
        if ($statusToken !== 200 || empty($tokenBody['access_token'])) {
            return ['status' => $statusToken, 'body' => $tokenBody];
        }

        try {
            $resp = Http::withToken($tokenBody['access_token'])
                ->withHeaders(['Accept' => 'application/json'])
                ->timeout(10)
                ->retry($this->retryAttempts, $this->retryDelayMs)
                ->get("{$this->base}/v2/checkout/orders/{$orderId}");

            $status = $resp->status();
            $body = $resp->successful() ? $resp->json() : ($resp->json() ?? ['error' => 'Respuesta inválida de PayPal']);

            if (!$resp->successful()) {
                Log::error('PayPalService::getOrder - fallo', [
                    'orderId' => $orderId,
                    'status' => $status,
                    'response' => $body,
                ]);
                $message = $body['message'] ?? ($body['error'] ?? 'Error al consultar la orden.');
                return ['status' => $status, 'body' => ['error' => "No fue posible obtener la orden: {$message}", 'raw' => $body]];
            }

            return ['status' => $status, 'body' => $body];
        } catch (Throwable $e) {
            Log::error('PayPalService::getOrder - excepción', [
                'orderId' => $orderId,
                'error' => $e->getMessage()
            ]);
            return ['status' => 500, 'body' => ['error' => 'Excepción al consultar la orden en PayPal.', 'details' => $e->getMessage()]];
        }
    }

    /**
     * Helper público para generar una referencia única de transacción si es necesario.
     */
    public function generarReferencia(string $prefijo = 'ORD'): string
    {
        return strtoupper($prefijo . '_' . Str::random(10));
    }
}