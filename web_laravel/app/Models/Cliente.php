<?php
/**
 * Nombre del archivo        : Cliente.php
 * Ruta                      : app/Models/Cliente.php
 * Descripción               : Modelo para la gestión completa de clientes y suscripciones,
 *                             incluyendo lógica de validación, transición de planes,
 *                             control de acceso y conservación de datos.
 * Fecha de creación         : 06/01/2026
 * Elaboró                   : Alan Osvaldo Basilio Delgado
 * Versión                   : 2.0
 * Fecha de mantenimiento    : 15/01/2026
 * Tipo de mantenimiento     : Refactorización y ampliación de funcionalidades
 * Descripción del mantenimiento: Se integran métodos para manejo completo de planes,
 *                                validación de suscripción, transición entre planes,
 *                                conservación de datos y reglas de negocio.
 * Responsable               : Alan Osvaldo Basilio Delgado
 * Revisor                   : Maileth Patiño Ensastegui
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Cliente extends Model
{
    use SoftDeletes;

    protected $table = 'clientes';

    protected $fillable = [
        'user_id',
        'nombre_titular',
        'email_contacto',
        'telefono',
        'plan',
        'fecha_inicio_suscripcion',
        'fecha_fin_suscripcion',
        'suscripcion_activa',
        'rfc_titular',
        'razon_social_titular',
        'plan_proximo_vencimiento', // Plan al que se cambiará tras vencimiento (downgrade programado)
    ];

    protected $casts = [
        'fecha_inicio_suscripcion' => 'datetime',
        'fecha_fin_suscripcion' => 'datetime',
        'suscripcion_activa' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relación con el usuario propietario de la cuenta.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope para filtrar solo clientes con suscripción activa.
     */
    public function scopeActivos($query)
    {
        return $query->where('suscripcion_activa', true);
    }

    /**
     * Scope para filtrar por plan.
     */
    public function scopePorPlan($query, string $plan)
    {
        return $query->where('plan', $plan);
    }

    /**
     * Scope para suscripciones próximas a vencer (dentro de X días).
     */
    public function scopeProximasVencer($query, int $dias = 7)
    {
        return $query->where('suscripcion_activa', true)
                    ->whereNotNull('fecha_fin_suscripcion')
                    ->whereBetween('fecha_fin_suscripcion', [
                        now(),
                        now()->addDays($dias)
                    ]);
    }

    /**
     * Scope para suscripciones vencidas.
     */
    public function scopeVencidas($query)
    {
        return $query->whereNotNull('fecha_fin_suscripcion')
                    ->where('fecha_fin_suscripcion', '<', now());
    }

    /**
     * Verifica si el cliente tiene plan premium.
     */
    public function esPremium(): bool
    {
        return strtolower($this->plan) === 'premium';
    }

    /**
     * Verifica si el cliente tiene plan básico.
     */
    public function esBasico(): bool
    {
        return strtolower($this->plan) === 'basico';
    }

    /**
     * Verifica si el cliente tiene plan estándar (si aplica).
     */
    public function esEstandar(): bool
    {
        return strtolower($this->plan) === 'estandar';
    }

    /**
     * Verifica si la suscripción está activa y vigente.
     */
    public function suscripcionVigente(): bool
    {
        if (!$this->suscripcion_activa) {
            return false;
        }

        if ($this->fecha_fin_suscripcion === null) {
            return true; // Suscripción sin fecha de fin
        }

        return $this->fecha_fin_suscripcion->isFuture();
    }

    /**
     * Obtiene los días restantes de la suscripción.
     * Retorna null si no hay fecha de fin.
     */
    public function diasRestantes(): ?int
    {
        if ($this->fecha_fin_suscripcion === null) {
            return null;
        }

        return now()->diffInDays($this->fecha_fin_suscripcion, false);
    }

    /**
     * Verifica si tiene datos fiscales completos.
     */
    public function tieneDatosFiscales(): bool
    {
        return !empty($this->rfc_titular) && !empty($this->razon_social_titular);
    }

    /**
     * Verifica si el cliente tiene acceso completo a la plataforma.
     * Acceso completo si la suscripción está vigente.
     */
    public function tieneAccesoCompleto(): bool
    {
        return $this->suscripcionVigente();
    }

    /**
     * Verifica si el cliente tiene acceso limitado (datos conservados pero sin plan activo).
     * Se conserva acceso por 1 mes tras vencimiento.
     */
    public function tieneAccesoLimitado(): bool
    {
        if ($this->suscripcionVigente()) {
            return true;
        }

        if ($this->fecha_fin_suscripcion === null) {
            return false;
        }

        $mesDesdeVencimiento = now()->diffInMonths($this->fecha_fin_suscripcion, false);

        return $mesDesdeVencimiento >= 0 && $mesDesdeVencimiento < 1;
    }

    /**
     * Verifica si la cuenta debe ser eliminada (2 meses o más sin plan activo).
     */
    public function debeSerEliminado(): bool
    {
        if ($this->suscripcionVigente()) {
            return false;
        }

        if ($this->fecha_fin_suscripcion === null) {
            return false;
        }

        $mesesDesdeVencimiento = now()->diffInMonths($this->fecha_fin_suscripcion, false);

        return $mesesDesdeVencimiento >= 2;
    }

    /**
     * Verifica si el cliente tiene un downgrade programado.
     * Retorna el nombre del plan al que se cambiará o null.
     */
    public function tieneDowngradeProgramado(): ?string
    {
        return $this->plan_proximo_vencimiento ? strtolower($this->plan_proximo_vencimiento) : null;
    }

    /**
     * Aplica el downgrade programado si la fecha de vencimiento ya pasó.
     * Retorna true si se aplicó el cambio, false si no.
     */
    public function aplicarDowngradeSiCorresponde(): bool
    {
        if (!$this->tieneDowngradeProgramado()) {
            return false;
        }

        if ($this->fecha_fin_suscripcion && $this->fecha_fin_suscripcion->isPast()) {
            $this->plan = $this->plan_proximo_vencimiento;
            $this->plan_proximo_vencimiento = null;
            $this->fecha_inicio_suscripcion = now();
            $this->fecha_fin_suscripcion = now()->addMonth();
            $this->suscripcion_activa = true;
            $this->save();

            return true;
        }

        return false;
    }

    /**
     * Retorna el límite de establecimientos según el plan.
     */
    public function limiteEstablecimientos(): int
    {
        if ($this->esPremium()) {
            return 6;
        } elseif ($this->esBasico()) {
            return 2;
        }
        return 0;
    }

    /**
     * Retorna el límite de promociones según el plan.
     */
    public function limitePromociones(): int
    {
        if ($this->esPremium()) {
            return 30;
        } elseif ($this->esBasico()) {
            return 10;
        }
        return 0;
    }

    /**
     * Retorna el límite de banners según el plan.
     */
    public function limiteBanners(): int
    {
        if ($this->esPremium()) {
            return 10;
        } elseif ($this->esBasico()) {
            return 3;
        }
        return 0;
    }

    /**
     * Verifica si el cliente ha excedido el límite de establecimientos.
     * Recibe la cantidad actual de establecimientos activos.
     */
    public function excedeLimiteEstablecimientos(int $cantidadActual): bool
    {
        return $cantidadActual > $this->limiteEstablecimientos();
    }

    /**
     * Verifica si el cliente ha excedido el límite de promociones.
     * Recibe la cantidad actual de promociones activas.
     */
    public function excedeLimitePromociones(int $cantidadActual): bool
    {
        return $cantidadActual > $this->limitePromociones();
    }

    /**
     * Verifica si el cliente ha excedido el límite de banners.
     * Recibe la cantidad actual de banners activos.
     */
    public function excedeLimiteBanners(int $cantidadActual): bool
    {
        return $cantidadActual > $this->limiteBanners();
    }
}