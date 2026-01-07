<?php

/**
 * Nombre del archivo        : RequireActivePlan.php
 * Descripción               : Middleware para validar plan activo del cliente
 * Fecha de creación         : 06/01/2026
 * Elaboró                   : Alan Osvaldo Basilio Delgado
 * Fecha de liberación       : 06/01/2026
 * Autorizó                  : Maileth Patiño Ensastegui
 * Version                   : 1.0
 * Fecha de mantenimiento    : 06/01/2026
 * Folio de mantenimiento    :
 * Tipo de mantenimiento     : Seguridad / Control de acceso
 * Descripción del mantenimiento: Creación de middleware para validar planes
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

        // Verificar si tiene un plan activo (basico, estandar o premium)
        $planesValidos = ['basico', 'estandar', 'premium'];
        
        if (empty($cliente->plan) || !in_array($cliente->plan, $planesValidos)) {
            return SweetAlertHelper::planRequerido('subscripcion.index');
        }

        return $next($request);
    }
}