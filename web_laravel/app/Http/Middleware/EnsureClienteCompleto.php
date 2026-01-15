<?php
/**
 * Nombre del archivo        : EnsureClienteCompleto.php
 * Ruta                      : app/Http/Middleware/EnsureClienteCompleto.php
 * Descripción               : Middleware que verifica que el usuario autenticado
 *                             tenga el "perfil de cliente" completado (datos mínimos)
 *                             antes de permitir el acceso a rutas protegidas que requieren
 *                             perfil activo. Responde en español y devuelve JSON
 *                             para peticiones AJAX (útil para manejar SweetAlert en frontend).
 *
 * Fecha de creación         : 06/01/2026
 * Elaboró                   : Alan Osvaldo Basilio Delgado
 * Versión                   : 1.0
 * Fecha de mantenimiento    : 18/01/2026
 * Tipo de mantenimiento     : Implementación inicial
 * Descripción del mantenimiento: Añadida respuesta JSON para AJAX y prevención de bucles de redirección.
 * Responsable               : Alan Osvaldo Basilio Delgado
 * Revisor                   : Maileth Patiño Ensastegui
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EnsureClienteCompleto
{
    /**
     * Determina si el perfil del cliente se considera "completo".
     * Puedes ajustar los campos necesarios según tus reglas de negocio.
     *
     * @param  \App\Models\Cliente|null  $cliente
     * @return bool
     */
    protected function isProfileComplete($cliente): bool
    {
        if (!$cliente) {
            return false;
        }

        // Campos mínimos requeridos para considerar el perfil como completado.
        // Ajusta según necesites (por ejemplo, agregar rfc_titular, razon_social_titular, etc.)
        $required = [
            'nombre_titular',
            'telefono',
            // 'email_contacto' // Si usas email_contacto en cliente en lugar de user->email
        ];

        foreach ($required as $field) {
            if (empty($cliente->{$field})) {
                return false;
            }
        }

        return true;
    }

    /**
     * Maneja la petición entrante.
     *
     * - Para peticiones AJAX/JSON devuelve JSON con status 'needs_profile' (en español).
     * - Para peticiones normales redirige a la vista para completar perfil y deja una variable
     *   de sesión (`swal`) con los datos que el frontend puede utilizar para mostrar SweetAlert.
     *
     * Evita bucles de redirección permitiendo acceder a las rutas relacionadas con completar perfil,
     * guardar perfil, logout y a rutas que explícitamente se configuren como "exentas".
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Si no está autenticado, dejamos que otros middlewares manejen la redirección.
        $user = Auth::user();
        if (!$user) {
            return $next($request);
        }

        // Rutas exentas: evita redirección cuando el usuario ya está en el flujo de completar perfil
        // o guardando el perfil, cerrando sesión, o en rutas públicas que definimos aquí.
        // Ajusta los nombres según tus rutas reales.
        $routeName = optional($request->route())->getName();
        $path = $request->path();

        $exemptRouteNames = [
            'clientes.complete_profile', // nombre de la vista para completar perfil (ajusta si es otro)
            'clientes.store',            // ruta que guarda el perfil
            'logout',
            'logout.perform',
            // añade aquí otros nombres de rutas que deben eludir el middleware
        ];

        // También permitir el acceso a cualquier ruta que empiece por 'api/' o 'webhook/' (si aplica)
        if (
            in_array($routeName, $exemptRouteNames, true)
            || $request->is('api/*')
            || $request->is('webhook/*')
            || $request->is('logout')
            || $request->ajax() && in_array($routeName, $exemptRouteNames, true)
        ) {
            return $next($request);
        }

        // Obtener el cliente relacionado (relación 'cliente' en el modelo User)
        $cliente = null;
        try {
            $cliente = $user->cliente ?? null;
        } catch (\Throwable $e) {
            // En caso de error inesperado, lo registramos y permitimos continuar para evitar bloquear la app.
            Log::warning('EnsureClienteCompleto: error al acceder a relation cliente', [
                'user_id' => $user->id ?? null,
                'error' => $e->getMessage(),
            ]);
            return $next($request);
        }

        // Si el perfil está completo, continúa
        if ($this->isProfileComplete($cliente)) {
            return $next($request);
        }

        // Si la petición espera JSON (AJAX / fetch), devolver respuesta JSON en español para manejar con SweetAlert
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'needs_profile',
                'message' => 'Debes completar tu perfil antes de acceder a esta sección.',
                'redirect' => route('clientes.complete_profile'),
            ], 403);
        }

        // Para peticiones normales, redirigir a la vista de completar perfil y dejar datos en sesión
        // para que el frontend (layout) muestre SweetAlert en español.
        // Estructura de sesión: session('swal') = ['icon'=>'warning','title'=>'...','text'=>'...']
        $swal = [
            'icon' => 'warning',
            'title' => 'Completa tu perfil',
            'text' => 'Antes de continuar debes completar los datos de tu cuenta (nombre y teléfono).',
            // opcional: botón de confirmación con color corporativo (naranja)
            'confirmButtonColor' => '#f97316',
        ];

        // Evitar bucle: si la ruta actual es ya la de completar perfil, no redirigir (aunque isProfileComplete=false).
        // Ya lo cubrimos arriba, pero por seguridad:
        if ($routeName === 'clientes.complete_profile' || $request->is('clientes/complete-profile')) {
            // Dejar pasar para que el usuario vea el formulario
            return $next($request);
        }

        // Flash para que la vista principal o layout muestre SweetAlert
        session()->flash('swal', $swal);

        return redirect()->route('clientes.complete_profile');
    }
}