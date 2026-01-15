<?php
/**
 * Nombre del archivo        : RequireActivePlan.php
 * Ruta                      : app/Http/Middleware/RequireActivePlan.php
 * Descripción               : Middleware para validar que el cliente tenga un plan activo.
 * Responde en español y devuelve JSON para peticiones AJAX (útil para SweetAlert),
 * o redirecciona con una sesión flash (`swal`) para mostrar SweetAlert en frontend.
 * Fecha de creación         : 06/01/2026
 * Elaboró                   : Alan Osvaldo Basilio Delgado
 * Fecha de liberación       : 06/01/2026
 * Autorizó                  : Maileth Patiño Ensastegui
 * Versión                   : 1.3
 * Fecha de mantenimiento    : 21/01/2026
 * Tipo de mantenimiento     : Corrección Bug (Laravel 12 Compatibility)
 * Descripción del mantenimiento: Se reemplaza la función helper obsoleta 'str_is()' por 'Str::is()'
 * para compatibilidad con Laravel 12.
 * Responsable               : Alan Osvaldo Basilio Delgado
 * Revisor                   : Maileth Patiño Ensastegui
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\SweetAlertHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; // <--- IMPORTANTE: Importar la clase Str
use Carbon\Carbon;

class RequireActivePlan
{
    /**
     * Lista de rutas (nombres o prefijos) exentas que NO deben ser bloqueadas por este middleware.
     * Ajustar según las rutas reales de la aplicación.
     *
     * @return array
     */
    protected function exemptRoutes(): array
    {
        return [
            'clientes.complete_profile',
            'clientes.store',
            'subscripcion.index',
            'subscripcion.pay',    // si existe flujo de pago
            'paypal.*',
            'webhook.*',
            'logout',
            'logout.perform',
            // rutas API públicas o healthchecks
            'health',
        ];
    }

    /**
     * Determina si la ruta actual está exenta del chequeo.
     */
    protected function isExempt(Request $request): bool
    {
        $route = $request->route();
        $name = optional($route)->getName();
        $path = $request->path();

        foreach ($this->exemptRoutes() as $exempt) {
            // Comparar por nombre de ruta usando Str::is
            if ($name && Str::is($exempt, $name)) {
                return true;
            }

            // Comparar por prefijo en path usando Str::is
            if (Str::is($exempt, $path) || Str::is($exempt . '/*', $path) || Str::is($exempt, $path)) {
                return true;
            }
        }

        // Permitir también peticiones a API públicas o rutas que empiecen por 'api/' o 'webhook/'
        if ($request->is('api/*') || $request->is('webhook/*')) {
            return true;
        }

        return false;
    }

    /**
     * Maneja la petición entrante.
     *
     * - Para peticiones AJAX devuelve JSON con la estructura necesaria para SweetAlert.
     * - Para peticiones normales intenta usar SweetAlertHelper::planRequerido(...) si está disponible,
     * o realiza un redirect()->route(...) con flash en session('swal').
     *
     * Evita bucles respetando las rutas exentas configuradas en `exemptRoutes()`.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si no hay usuario autenticado, dejamos pasar y que auth middleware lo gestione.
        if (!$user) {
            return $next($request);
        }

        // Evitar aplicar middleware en rutas exentas
        if ($this->isExempt($request)) {
            return $next($request);
        }

        // Obtener cliente asociado
        $cliente = null;
        try {
            $cliente = $user->cliente ?? null;
        } catch (\Throwable $e) {
            Log::warning('RequireActivePlan: error al acceder a relación cliente', [
                'user_id' => $user->id ?? null,
                'error' => $e->getMessage(),
            ]);

            // Si no podemos determinar cliente, pedimos completar registro
            return $this->respuestaPlanIncompleto($request, 'clientes.complete_profile', 'No se encontró información de cliente. Completa tu perfil.');
        }

        // Si no hay cliente, pedir finalizar registro
        if (!$cliente) {
            return $this->respuestaPlanIncompleto($request, 'clientes.complete_profile', 'Por favor completa tu perfil antes de usar esta sección.');
        }

        // Intentar aplicar downgrade programado si corresponde (ejecución de mantenimiento en línea)
        try {
            if (method_exists($cliente, 'aplicarDowngradeSiCorresponde')) {
                $cliente->aplicarDowngradeSiCorresponde();
                $cliente->refresh();
            }
        } catch (\Throwable $e) {
            Log::warning('RequireActivePlan: error aplicando downgrade programado', [
                'cliente_id' => $cliente->id,
                'error' => $e->getMessage(),
            ]);
            // Continuar sin bloquear al usuario — no queremos causar errores por mantenimiento interno
        }

        // Si la fecha de fin existe y ya pasó, marcar como inactiva (persistir cambio)
        try {
            if ($cliente->fecha_fin_suscripcion && Carbon::parse($cliente->fecha_fin_suscripcion)->isPast()) {
                if ($cliente->suscripcion_activa) {
                    $cliente->suscripcion_activa = false;
                    $cliente->save();
                }
            }
        } catch (\Throwable $e) {
            Log::warning('RequireActivePlan: error al evaluar/actualizar fecha_fin_suscripcion', [
                'cliente_id' => $cliente->id,
                'error' => $e->getMessage(),
            ]);
            // No bloqueamos la petición; continuamos con la evaluación siguiente
        }

        // Si la cuenta fue marcada para eliminación por inactividad prolongada, informar
        try {
            if (method_exists($cliente, 'debeSerEliminado') && $cliente->debeSerEliminado()) {
                $msg = 'Tu cuenta está inactiva desde hace tiempo y puede ser eliminada. Contacta soporte si deseas reactivarla.';
                return $this->respuestaPlanInactivo($request, 'support.index', $msg, 403);
            }
        } catch (\Throwable $e) {
            Log::warning('RequireActivePlan: error al evaluar debeSerEliminado', [
                'cliente_id' => $cliente->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Validar que exista plan y esté activo
        if (empty($cliente->plan) || !$cliente->suscripcion_activa) {
            $mensaje = 'Necesitas un plan activo para acceder a esta sección. Visita la página de suscripción para contratar o reactivar tu plan.';
            return $this->respuestaPlanInactivo($request, 'subscripcion.index', $mensaje, 403);
        }

        // Todo OK: continuar con la petición
        return $next($request);
    }

    /**
     * Respuesta cuando el registro/perfil está incompleto.
     * Si la petición es AJAX devuelve JSON; si no, intenta usar SweetAlertHelper o redirige con session flash.
     */
    protected function respuestaPlanIncompleto(Request $request, string $routeName, string $message = null, int $status = 403)
    {
        $msg = $message ?? 'Debes completar tu registro para continuar.';
        $redirect = route($routeName);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'needs_profile',
                'message' => $msg,
                'redirect' => $redirect,
                'swal' => [
                    'icon' => 'warning',
                    'title' => 'Completa tu perfil',
                    'text' => $msg,
                    'confirmButtonColor' => '#f97316'
                ]
            ], $status);
        }

        // Intentar usar SweetAlertHelper si existe (retorna Response o Redirect)
        try {
            if (class_exists(SweetAlertHelper::class) && method_exists(SweetAlertHelper::class, 'registroIncompleto')) {
                return SweetAlertHelper::registroIncompleto($routeName, $msg);
            }
        } catch (\Throwable $e) {
            Log::warning('RequireActivePlan: SweetAlertHelper::registroIncompleto falló', [
                'route' => $routeName,
                'error' => $e->getMessage(),
            ]);
        }

        // Fallback: redirigir con flash para que el layout muestre SweetAlert usando session('swal')
        session()->flash('swal', [
            'icon' => 'warning',
            'title' => 'Completa tu perfil',
            'text' => $msg,
            'confirmButtonColor' => '#f97316'
        ]);

        return redirect()->route($routeName);
    }

    /**
     * Respuesta cuando no existe plan activo o está inactivo.
     * Similar a respuestaPlanIncompleto pero con mensaje específico de plan.
     */
    protected function respuestaPlanInactivo(Request $request, string $routeName, string $message = null, int $status = 403)
    {
        $msg = $message ?? 'Tu plan no está activo. Ve a la sección de suscripciones para más detalles.';
        $redirect = route($routeName);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'plan_required',
                'message' => $msg,
                'redirect' => $redirect,
                'swal' => [
                    'icon' => 'warning',
                    'title' => 'Plan requerido',
                    'text' => $msg,
                    'confirmButtonColor' => '#f97316'
                ]
            ], $status);
        }

        // Intentar usar SweetAlertHelper si existe
        try {
            if (class_exists(SweetAlertHelper::class) && method_exists(SweetAlertHelper::class, 'planRequerido')) {
                return SweetAlertHelper::planRequerido($routeName, $msg);
            }
        } catch (\Throwable $e) {
            Log::warning('RequireActivePlan: SweetAlertHelper::planRequerido falló', [
                'route' => $routeName,
                'error' => $e->getMessage(),
            ]);
        }

        // Fallback: redirigir con flash para que el layout muestre SweetAlert usando session('swal')
        session()->flash('swal', [
            'icon' => 'warning',
            'title' => 'Plan requerido',
            'text' => $msg,
            'confirmButtonColor' => '#f97316'
        ]);

        return redirect()->route($routeName);
    }
}