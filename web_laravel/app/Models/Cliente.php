<?php
/**
 * Nombre del archivo        : Cliente.php
 * Ruta                      : app/Models/Cliente.php
 * Descripción               : Modelo Eloquent para la gestión de Clientes.
 * Representa al titular de la cuenta y su suscripción.
 * Fecha de creación         : 06/01/2026
 * Elaboró                   : Alan Osvaldo Basilio Delgado
 * Versión                   : 1.5
 * Fecha de mantenimiento    : 21/01/2026
 * Tipo de mantenimiento     : Estructural / Base de Datos
 * Descripción del mantenimiento: 
 * - Se agrega la relación 'establecimientos' (HasMany) para corregir la navegación
 * en los controladores (User -> Cliente -> Establecimientos).
 * - Se añade 'plan_proximo_vencimiento' al fillable para permitir downgrades programados.
 * Responsable               : Alan Osvaldo Basilio Delgado
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'plan_proximo_vencimiento', // <--- AGREGADO: Necesario para la lógica de downgrade en el Controller
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

    /* =========================================================================
     * RELACIONES
     * ========================================================================= */

    /**
     * Relación con el usuario propietario de la cuenta (User -> Cliente).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con los establecimientos del cliente (Cliente -> Establecimientos).
     * CORRECCIÓN IMPORTANTE: Permite acceder a $cliente->establecimientos
     */
    public function establecimientos(): HasMany
    {
        return $this->hasMany(Establecimientos::class, 'cliente_id');
    }

    /* =========================================================================
     * SCOPES (Filtros de consulta)
     * ========================================================================= */

    /**
     * Filtra solo clientes con suscripción activa.
     */
    public function scopeActivos($query)
    {
        return $query->where('suscripcion_activa', true);
    }

    /**
     * Filtra por tipo de plan.
     */
    public function scopePorPlan($query, string $plan)
    {
        return $query->where('plan', $plan);
    }

    /**
     * Filtra suscripciones próximas a vencer (dentro de X días).
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
     * Filtra suscripciones que ya han vencido.
     */
    public function scopeVencidas($query)
    {
        return $query->whereNotNull('fecha_fin_suscripcion')
                    ->where('fecha_fin_suscripcion', '<', now());
    }

    /* =========================================================================
     * MÉTODOS DE AYUDA (Helpers)
     * ========================================================================= */

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
     * Verifica si la suscripción está activa y la fecha no ha expirado.
     */
    public function suscripcionVigente(): bool
    {
        if (!$this->suscripcion_activa) {
            return false;
        }

        // Si es null, asumimos vitalicia o indefinida
        if ($this->fecha_fin_suscripcion === null) {
            return true;
        }

        return $this->fecha_fin_suscripcion->isFuture();
    }

    /**
     * Obtiene los días restantes de la suscripción.
     * Retorna null si es indefinida.
     */
    public function diasRestantes(): ?int
    {
        if ($this->fecha_fin_suscripcion === null) {
            return null;
        }

        return now()->diffInDays($this->fecha_fin_suscripcion, false);
    }

    /**
     * Verifica si tiene datos fiscales completos para facturación.
     */
    public function tieneDatosFiscales(): bool
    {
        return !empty($this->rfc_titular) && !empty($this->razon_social_titular);
    }

    /**
     * Aplica el downgrade si existe uno programado y la fecha de suscripción venció.
     * (Método auxiliar llamado por Middleware/Controller)
     */
    public function aplicarDowngradeSiCorresponde(): void
    {
        if ($this->plan_proximo_vencimiento && 
            $this->fecha_fin_suscripcion && 
            $this->fecha_fin_suscripcion->isPast()) {
            
            $this->plan = $this->plan_proximo_vencimiento;
            $this->plan_proximo_vencimiento = null;
            // Aquí se podría renovar la fecha o desactivar suscripción según lógica de negocio
            // Por defecto, mantenemos suscripción activa pero con plan degradado
            $this->save();
        }
    }
}