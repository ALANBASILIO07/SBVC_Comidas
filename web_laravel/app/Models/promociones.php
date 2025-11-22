<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * Modelo Promocion
 * 
 * Representa una promoción o descuento ofrecido por un establecimiento.
 * Incluye validación de vigencia y estado activo.
 */
class Promocion extends Model
{
    /**
     * La tabla asociada al modelo.
     */
    protected $table = 'promociones';

    /**
     * Los atributos que son asignables en masa.
     */
    protected $fillable = [
        'establecimientos_id',
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_final',
        'activo',
    ];

    /**
     * Los atributos que deben ser casteados a tipos nativos.
     */
    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_final' => 'datetime',
        'activo' => 'boolean',
    ];

    /**
     * Relación con el establecimiento al que pertenece la promoción.
     */
    public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimiento::class, 'establecimientos_id');
    }

    /**
     * Scope para filtrar solo promociones activas.
     */
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para filtrar promociones vigentes (dentro del rango de fechas).
     */
    public function scopeVigentes($query)
    {
        $ahora = now();
        return $query->where('fecha_inicio', '<=', $ahora)
                    ->where('fecha_final', '>=', $ahora);
    }

    /**
     * Scope para promociones activas y vigentes.
     */
    public function scopeDisponibles($query)
    {
        return $query->activas()->vigentes();
    }

    /**
     * Scope para promociones de un establecimiento específico.
     */
    public function scopePorEstablecimiento($query, int $establecimientoId)
    {
        return $query->where('establecimientos_id', $establecimientoId);
    }

    /**
     * Scope para promociones próximas a iniciar (dentro de X días).
     */
    public function scopeProximasIniciar($query, int $dias = 7)
    {
        return $query->where('fecha_inicio', '>', now())
                    ->where('fecha_inicio', '<=', now()->addDays($dias))
                    ->orderBy('fecha_inicio');
    }

    /**
     * Scope para promociones próximas a vencer (dentro de X días).
     */
    public function scopeProximasVencer($query, int $dias = 3)
    {
        return $query->where('fecha_final', '>', now())
                    ->where('fecha_final', '<=', now()->addDays($dias))
                    ->orderBy('fecha_final');
    }

    /**
     * Scope para promociones expiradas.
     */
    public function scopeExpiradas($query)
    {
        return $query->where('fecha_final', '<', now());
    }

    /**
     * Scope para ordenar por más recientes.
     */
    public function scopeRecientes($query)
    {
        return $query->orderByDesc('created_at');
    }

    /**
     * Verifica si la promoción está vigente actualmente.
     */
    public function estaVigente(): bool
    {
        $ahora = now();
        return $this->fecha_inicio <= $ahora && $this->fecha_final >= $ahora;
    }

    /**
     * Verifica si la promoción está disponible (activa y vigente).
     */
    public function estaDisponible(): bool
    {
        return $this->activo && $this->estaVigente();
    }

    /**
     * Verifica si la promoción ya expiró.
     */
    public function haExpirado(): bool
    {
        return $this->fecha_final < now();
    }

    /**
     * Verifica si la promoción aún no ha iniciado.
     */
    public function noHaIniciado(): bool
    {
        return $this->fecha_inicio > now();
    }

    /**
     * Obtiene los días restantes de la promoción.
     * Retorna null si ya expiró o no ha iniciado.
     */
    public function diasRestantes(): ?int
    {
        if ($this->haExpirado() || $this->noHaIniciado()) {
            return null;
        }

        return now()->diffInDays($this->fecha_final, false);
    }

    /**
     * Obtiene las horas restantes de la promoción.
     * Retorna null si ya expiró o no ha iniciado.
     */
    public function horasRestantes(): ?int
    {
        if ($this->haExpirado() || $this->noHaIniciado()) {
            return null;
        }

        return now()->diffInHours($this->fecha_final, false);
    }

    /**
     * Obtiene el estado de la promoción como texto.
     */
    public function estadoTexto(): string
    {
        if (!$this->activo) {
            return 'Inactiva';
        }

        if ($this->noHaIniciado()) {
            return 'Próximamente';
        }

        if ($this->haExpirado()) {
            return 'Expirada';
        }

        if ($this->estaVigente()) {
            return 'Vigente';
        }

        return 'Desconocido';
    }

    /**
     * Obtiene la duración total de la promoción en días.
     */
    public function duracionDias(): int
    {
        return $this->fecha_inicio->diffInDays($this->fecha_final);
    }

    /**
     * Verifica si la promoción está por vencer (menos de X días).
     */
    public function estaPorVencer(int $diasUmbral = 3): bool
    {
        if (!$this->estaVigente()) {
            return false;
        }

        $diasRestantes = $this->diasRestantes();
        return $diasRestantes !== null && $diasRestantes <= $diasUmbral;
    }

    /**
     * Obtiene un resumen de la vigencia de la promoción.
     */
    public function resumenVigencia(): string
    {
        if ($this->noHaIniciado()) {
            $dias = now()->diffInDays($this->fecha_inicio);
            return "Inicia en {$dias} " . ($dias === 1 ? 'día' : 'días');
        }

        if ($this->haExpirado()) {
            $dias = $this->fecha_final->diffInDays(now());
            return "Expiró hace {$dias} " . ($dias === 1 ? 'día' : 'días');
        }

        if ($this->estaVigente()) {
            $dias = $this->diasRestantes();
            if ($dias === 0) {
                $horas = $this->horasRestantes();
                return "Expira en {$horas} " . ($horas === 1 ? 'hora' : 'horas');
            }
            return "Expira en {$dias} " . ($dias === 1 ? 'día' : 'días');
        }

        return 'Estado desconocido';
    }

    /**
     * Formatea el período de vigencia.
     */
    public function periodoVigencia(): string
    {
        return sprintf(
            'Del %s al %s',
            $this->fecha_inicio->format('d/m/Y'),
            $this->fecha_final->format('d/m/Y')
        );
    }
}