<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Banner
 * 
 * Representa un banner publicitario de un establecimiento.
 * Incluye validación de vigencia, estado activo y gestión de imágenes.
 */
class Banner extends Model
{
    use SoftDeletes;

    /**
     * La tabla asociada al modelo.
     */
    protected $table = 'banners';

    /**
     * Los atributos que son asignables en masa.
     */
    protected $fillable = [
        'establecimiento_id',
        'titulo_banner',
        'descripcion_banner',
        'imagen_banner',
        'url_destino',
        'fecha_inicio',
        'fecha_fin',
        'activo',
    ];

    /**
     * Los atributos que deben ser casteados a tipos nativos.
     */
    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'activo' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relación con el establecimiento al que pertenece el banner.
     */
    public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimientos::class, 'establecimiento_id');
    }

    /**
     * Scope para filtrar solo banners activos.
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para filtrar banners vigentes (dentro del rango de fechas).
     */
    public function scopeVigentes($query)
    {
        $ahora = now();
        return $query->where('fecha_inicio', '<=', $ahora)
                    ->where('fecha_fin', '>=', $ahora);
    }

    /**
     * Scope para banners activos y vigentes.
     */
    public function scopeDisponibles($query)
    {
        return $query->activos()->vigentes();
    }

    /**
     * Scope para banners de un establecimiento específico.
     */
    public function scopePorEstablecimiento($query, int $establecimientoId)
    {
        return $query->where('establecimiento_id', $establecimientoId);
    }

    /**
     * Scope para banners próximos a vencer (dentro de X días).
     */
    public function scopeProximosVencer($query, int $dias = 3)
    {
        return $query->where('fecha_fin', '>', now())
                    ->where('fecha_fin', '<=', now()->addDays($dias))
                    ->orderBy('fecha_fin');
    }

    /**
     * Scope para banners expirados.
     */
    public function scopeExpirados($query)
    {
        return $query->where('fecha_fin', '<', now());
    }

    /**
     * Scope para ordenar por más recientes.
     */
    public function scopeRecientes($query)
    {
        return $query->orderByDesc('created_at');
    }

    /**
     * Verifica si el banner está vigente actualmente.
     */
    public function estaVigente(): bool
    {
        $ahora = now();
        return $this->fecha_inicio <= $ahora && $this->fecha_fin >= $ahora;
    }

    /**
     * Verifica si el banner está disponible (activo y vigente).
     */
    public function estaDisponible(): bool
    {
        return $this->activo && $this->estaVigente();
    }

    /**
     * Verifica si el banner ya expiró.
     */
    public function haExpirado(): bool
    {
        return $this->fecha_fin < now();
    }

    /**
     * Verifica si el banner aún no ha iniciado.
     */
    public function noHaIniciado(): bool
    {
        return $this->fecha_inicio > now();
    }

    /**
     * Obtiene los días restantes del banner.
     * Retorna null si ya expiró o no ha iniciado.
     */
    public function diasRestantes(): ?int
    {
        if ($this->haExpirado() || $this->noHaIniciado()) {
            return null;
        }

        return now()->diffInDays($this->fecha_fin, false);
    }

    /**
     * Obtiene las horas restantes del banner.
     * Retorna null si ya expiró o no ha iniciado.
     */
    public function horasRestantes(): ?int
    {
        if ($this->haExpirado() || $this->noHaIniciado()) {
            return null;
        }

        return now()->diffInHours($this->fecha_fin, false);
    }

    /**
     * Obtiene el estado del banner como texto.
     */
    public function estadoTexto(): string
    {
        if (!$this->activo) {
            return 'Inactivo';
        }

        if ($this->noHaIniciado()) {
            return 'Programado';
        }

        if ($this->haExpirado()) {
            return 'Expirado';
        }

        if ($this->estaVigente()) {
            return 'Activo';
        }

        return 'Desconocido';
    }

    /**
     * Obtiene la duración total del banner en días.
     */
    public function duracionDias(): int
    {
        return $this->fecha_inicio->diffInDays($this->fecha_fin);
    }

    /**
     * Verifica si el banner está por vencer (menos de X días).
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
     * Obtiene la URL completa de la imagen del banner.
     */
    public function urlImagen(): string
    {
        // Si ya es una URL completa, devolverla tal cual
        if (filter_var($this->imagen_banner, FILTER_VALIDATE_URL)) {
            return $this->imagen_banner;
        }

        // Si es una ruta relativa, construir URL
        return asset('storage/' . $this->imagen_banner);
    }

    /**
     * Verifica si el banner tiene URL de destino.
     */
    public function tieneUrlDestino(): bool
    {
        return !empty($this->url_destino);
    }

    /**
     * Obtiene un resumen de la vigencia del banner.
     */
    public function resumenVigencia(): string
    {
        if ($this->noHaIniciado()) {
            $dias = now()->diffInDays($this->fecha_inicio);
            return "Inicia en {$dias} " . ($dias === 1 ? 'día' : 'días');
        }

        if ($this->haExpirado()) {
            $dias = $this->fecha_fin->diffInDays(now());
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
            $this->fecha_fin->format('d/m/Y')
        );
    }

    /**
     * Obtiene el color de estado para UI.
     */
    public function colorEstado(): string
    {
        if (!$this->activo) {
            return 'gray';
        }

        if ($this->noHaIniciado()) {
            return 'blue';
        }

        if ($this->haExpirado()) {
            return 'red';
        }

        if ($this->estaPorVencer()) {
            return 'orange';
        }

        if ($this->estaVigente()) {
            return 'green';
        }

        return 'gray';
    }
}