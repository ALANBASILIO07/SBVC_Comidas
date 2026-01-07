<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Resena extends Model
{
    use HasFactory;

    protected $table = 'resenas';

    protected $fillable = [
        'establecimiento_id',
        'cliente_nombre',
        'cliente_email',
        'puntuacion',
        'comentario',
        'verificada',
        'activa',
    ];

    protected $casts = [
        'puntuacion' => 'integer',
        'verificada' => 'boolean',
        'activa' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    /**
     * Relación con Establecimiento
     */
    public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimientos::class, 'establecimiento_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope para filtrar solo reseñas activas
     */
    public function scopeActivas(Builder $query): Builder
    {
        return $query->where('activa', true);
    }

    /**
     * Scope para filtrar por establecimiento
     */
    public function scopePorEstablecimiento(Builder $query, $establecimientoId): Builder
    {
        return $query->where('establecimiento_id', $establecimientoId);
    }

    /**
     * Scope para filtrar por puntuación
     */
    public function scopePorPuntuacion(Builder $query, $puntuacion): Builder
    {
        return $query->where('puntuacion', $puntuacion);
    }

    /**
     * Scope para ordenar por más recientes
     */
    public function scopeMasRecientes(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope para ordenar por más antiguas
     */
    public function scopeMasAntiguas(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'asc');
    }

    /**
     * Scope para ordenar por mejor calificadas
     */
    public function scopeMejorCalificadas(Builder $query): Builder
    {
        return $query->orderBy('puntuacion', 'desc')
                     ->orderBy('created_at', 'desc');
    }

    /**
     * Scope para ordenar por peor calificadas
     */
    public function scopePeorCalificadas(Builder $query): Builder
    {
        return $query->orderBy('puntuacion', 'asc')
                     ->orderBy('created_at', 'desc');
    }

    /**
     * Scope para filtrar solo reseñas verificadas
     */
    public function scopeVerificadas(Builder $query): Builder
    {
        return $query->where('verificada', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos Helper
    |--------------------------------------------------------------------------
    */

    /**
     * Obtener estrellas en formato texto (★★★★★)
     */
    public function estrellasTexto(): string
    {
        $estrellas = str_repeat('★', $this->puntuacion);
        $vacias = str_repeat('☆', 5 - $this->puntuacion);
        return $estrellas . $vacias;
    }

    /**
     * Obtener el color según la puntuación
     */
    public function colorPuntuacion(): string
    {
        return match($this->puntuacion) {
            5 => 'text-green-500',
            4 => 'text-blue-500',
            3 => 'text-yellow-500',
            2 => 'text-orange-500',
            1 => 'text-red-500',
            default => 'text-gray-500',
        };
    }

    /**
     * Obtener tiempo relativo (hace 2 días, hace 1 semana, etc.)
     */
    public function tiempoRelativo(): string
    {
        return $this->created_at->locale('es')->diffForHumans();
    }

    /**
     * Obtener fecha formateada en español
     */
    public function fechaFormateada(): string
    {
        return $this->created_at->locale('es')->isoFormat('D [de] MMMM[,] YYYY');
    }
}
