<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Cliente
 * 
 * Representa al titular de una cuenta/suscripción en la plataforma.
 * Contiene información del cliente que paga el servicio y datos de su suscripción.
 */
class Cliente extends Model
{
    use SoftDeletes;

    /**
     * La tabla asociada al modelo.
     */
    protected $table = 'clientes';

    /**
     * Los atributos que son asignables en masa.
     */
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
    ];

    /**
     * Los atributos que deben ser casteados a tipos nativos.
     */
    protected $casts = [
        'fecha_inicio_suscripcion' => 'datetime',
        'fecha_fin_suscripcion' => 'datetime',
        'suscripcion_activa' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relación con el usuario propietario de la cuenta.
     */
    public function usuario(): BelongsTo
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
        return $this->plan === 'premium';
    }

    /**
     * Verifica si el cliente tiene plan estándar.
     */
    public function esEstandar(): bool
    {
        return $this->plan === 'estandar';
    }

    /**
     * Verifica si el cliente tiene plan básico.
     */
    public function esBasico(): bool
    {
        return $this->plan === 'basico';
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
} 