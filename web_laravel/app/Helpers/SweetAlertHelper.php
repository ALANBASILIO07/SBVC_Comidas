<?php

/**
 * Nombre del archivo        : SweetAlertHelper.php
 * Descripción               : Helper para mensajes SweetAlert estandarizados
 * Fecha de creación         : 06/01/2026
 * Elaboró                   : Alan Osvaldo Basilio Delgado
 * Fecha de liberación       : 06/01/2026
 * Autorizó                  : Maileth Patiño Ensastegui
 * Version                   : 1.0
 * Fecha de mantenimiento    : 06/01/2026
 * Folio de mantenimiento    :
 * Tipo de mantenimiento     : Seguridad / UX
 * Descripción del mantenimiento: Creación de helper para SweetAlert
 * Responsable               : Alan Osvaldo Basilio Delgado
 * Revisor                   : Maileth Patiño Ensastegui
 */

namespace App\Helpers;

class SweetAlertHelper
{
    /**
     * Mensaje cuando se requiere un plan activo
     */
    public static function planRequerido(string $routeName = 'subscripcion.index')
    {
        return redirect()->route($routeName)->with('swal', [
            'icon' => 'warning',
            'title' => 'Plan requerido',
            'text' => 'Necesitas un plan activo para acceder a esta sección.',
            'confirmButtonText' => 'Ver planes',
            'confirmButtonColor' => '#F7941D',
            'draggable' => true,
        ]);
    }

    /**
     * Mensaje cuando el registro está incompleto
     */
    public static function registroIncompleto(string $routeName = 'registro.completar')
    {
        return redirect()->route($routeName)->with('swal', [
            'icon' => 'info',
            'title' => 'Completa tu registro',
            'text' => 'Por favor completa tu información de cliente para continuar.',
            'confirmButtonText' => 'Completar ahora',
            'confirmButtonColor' => '#F7941D',
            'draggable' => true,
        ]);
    }

    /**
     * Mensaje de acceso denegado genérico
     */
    public static function accesoDenegado(string $routeName = 'dashboard')
    {
        return redirect()->route($routeName)->with('swal', [
            'icon' => 'error',
            'title' => 'Acceso denegado',
            'text' => 'No tienes permisos para acceder a esta sección.',
            'confirmButtonText' => 'Entendido',
            'confirmButtonColor' => '#F7941D',
            'draggable' => true,
        ]);
    }

    /**
     * Mensaje de éxito genérico
     */
    public static function exito(string $mensaje, string $routeName = null)
    {
        $redirect = $routeName ? redirect()->route($routeName) : back();
        
        return $redirect->with('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => $mensaje,
            'confirmButtonText' => 'Continuar',
            'confirmButtonColor' => '#F7941D',
            'draggable' => true,
            'timer' => 3000
        ]);
    }

    /**
     * Mensaje de advertencia genérico
     */
    public static function advertencia(string $mensaje, string $routeName = null)
    {
        $redirect = $routeName ? redirect()->route($routeName) : back();
        
        return $redirect->with('swal', [
            'icon' => 'warning',
            'title' => 'Advertencia',
            'text' => $mensaje,
            'confirmButtonText' => 'Entendido',
            'confirmButtonColor' => '#F7941D',
            'draggable' => true,
        ]);
    }
}