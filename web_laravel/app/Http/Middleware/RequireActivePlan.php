<?php
/**
 * Nombre del archivo        : RequireActivePlan.php
 * Descripción               : Middleware para validar plan activo del cliente
 * Fecha de creación         : 06/01/2026
 * Elaboró                   : Alan Osvaldo Basilio Delgado
 * Fecha de liberación       : 06/01/2026
 * Autorizó                  : Maileth Patiño Ensastegui
 * Version                   : 1.1
 * Fecha de mantenimiento    : 12/01/2026
 * Folio de mantenimiento    :
 * Tipo de mantenimiento     : Seguridad / Control de acceso
 * Descripción del mantenimiento: Validar que la suscripción esté realmente activa (suscripcion_activa).
 * Responsable               : Alan Osvaldo Basilio Delgado
 * Revisor                   : Maileth Patiño Ensastegui
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\SweetAlertHelper;

class RequireActivePlan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Verificar si el usuario tiene un cliente asociado
        if (!$user || !$user->cliente) {
            return SweetAlertHelper::registroIncompleto('registro.completar');
        }

        $cliente = $user->cliente;

        // Si la fecha de fin existe y ya pasó, marcar como inactiva
        if ($cliente->fecha_fin_suscripcion && $cliente->fecha_fin_suscripcion->isPast()) {
            // Actualizamos de forma segura
            try {
                $cliente->suscripcion_activa = false;
                $cliente->save();
            } catch (\Throwable $e) {
                // Log opcional (no detener el flujo por el log)
                \Log::warning('RequireActivePlan: error al actualizar suscripcion_activa', [
                    'cliente_id' => $cliente->id,
                    'error' => $e->getMessage()
                ]);
            }

            return SweetAlertHelper::planRequerido('subscripcion.index');
        }

        // Validar que exista un plan y que la suscripción esté activa
        if (empty($cliente->plan) || !$cliente->suscripcion_activa) {
            return SweetAlertHelper::planRequerido('subscripcion.index');
        }

        return $next($request);
    }
}