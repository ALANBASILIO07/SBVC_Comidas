<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Modelo Categoria
 * 
 * Representa una categoría de establecimiento de comida.
 * Ejemplos: Restaurante, Cafetería, Comida Rápida, Panadería, etc.
 */
class Categoria extends Model
{
    /**
     * La tabla asociada al modelo.
     */
    protected $table = 'categorias';

    /**
     * Los atributos que son asignables en masa.
     */
    protected $fillable = [
        'nombre_categoria',
        'descripcion_categoria',
    ];

    /**
     * Los atributos que deben ser casteados a tipos nativos.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación uno a muchos con establecimientos.
     * Una categoría puede tener muchos establecimientos.
     */
    public function establecimientos(): HasMany
    {
        return $this->hasMany(Establecimientos::class, 'categoria_id');
    }

    /**
     * Scope para buscar categorías por nombre.
     */
    public function scopeBuscar($query, string $termino)
    {
        return $query->where('nombre_categoria', 'like', "%{$termino}%");
    }

    /**
     * Scope para ordenar alfabéticamente.
     */
    public function scopeAlfabetico($query)
    {
        return $query->orderBy('nombre_categoria');
    }

    /**
     * Scope para categorías con establecimientos activos.
     */
    public function scopeConEstablecimientos($query)
    {
        return $query->has('establecimientos');
    }

    /**
     * Scope para categorías más populares (con más establecimientos).
     */
    public function scopePopulares($query, int $limite = 10)
    {
        return $query->withCount('establecimientos')
                    ->orderByDesc('establecimientos_count')
                    ->limit($limite);
    }

    /**
     * Obtiene el total de establecimientos en esta categoría.
     */
    public function totalEstablecimientos(): int
    {
        return $this->establecimientos()->count();
    }

    /**
     * Obtiene el total de establecimientos activos en esta categoría.
     */
    public function totalEstablecimientosActivos(): int
    {
        return $this->establecimientos()->where('activo', true)->count();
    }

    /**
     * Obtiene el total de establecimientos verificados en esta categoría.
     */
    public function totalEstablecimientosVerificados(): int
    {
        return $this->establecimientos()
                    ->where('verificacion_establecimiento', true)
                    ->count();
    }

    /**
     * Verifica si la categoría tiene establecimientos.
     */
    public function tieneEstablecimientos(): bool
    {
        return $this->establecimientos()->exists();
    }

    /**
     * Genera un slug para la categoría.
     */
    public function slug(): string
    {
        return Str::slug($this->nombre_categoria);
    }

    /**
     * Obtiene el nombre formateado de la categoría.
     */
    public function nombreFormateado(): string
    {
        return Str::title($this->nombre_categoria);
    }

    /**
     * Obtiene establecimientos activos de esta categoría.
     */
    public function establecimientosActivos()
    {
        return $this->establecimientos()->where('activo', true);
    }

    /**
     * Obtiene establecimientos verificados de esta categoría.
     */
    public function establecimientosVerificados()
    {
        return $this->establecimientos()->where('verificacion_establecimiento', true);
    }

    /**
     * Obtiene estadísticas de la categoría.
     */
    public function estadisticas(): array
    {
        return [
            'total_establecimientos' => $this->totalEstablecimientos(),
            'establecimientos_activos' => $this->totalEstablecimientosActivos(),
            'establecimientos_verificados' => $this->totalEstablecimientosVerificados(),
            'valoracion_promedio' => $this->establecimientos()
                                          ->where('activo', true)
                                          ->avg('valoracion_promedio') ?? 0,
        ];
    }

    /**
     * Obtiene los establecimientos mejor valorados de esta categoría.
     */
    public function mejoresEstablecimientos(int $limite = 5)
    {
        return $this->establecimientos()
                    ->where('activo', true)
                    ->orderByDesc('valoracion_promedio')
                    ->orderByDesc('total_resenas')
                    ->limit($limite)
                    ->get();
    }

    /**
     * Obtiene un resumen de la categoría.
     */
    public function resumen(): string
    {
        $total = $this->totalEstablecimientosActivos();
        $nombrePlural = $total === 1 ? 'establecimiento' : 'establecimientos';
        
        return "{$this->nombre_categoria} - {$total} {$nombrePlural}";
    }

    /**
     * Obtiene la descripción truncada para vista previa.
     */
    public function descripcionCorta(int $caracteres = 100): ?string
    {
        if (empty($this->descripcion_categoria)) {
            return null;
        }

        return Str::limit($this->descripcion_categoria, $caracteres);
    }

    /**
     * Verifica si es una categoría popular (tiene muchos establecimientos).
     */
    public function esPopular(int $umbral = 10): bool
    {
        return $this->totalEstablecimientos() >= $umbral;
    }
} 