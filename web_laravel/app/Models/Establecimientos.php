<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Establecimiento
 * 
 * Representa un negocio o lugar de comida registrado en la plataforma.
 * Contiene información comercial, ubicación, servicios y valoraciones.
 */
class Establecimientos extends Model
{
    use SoftDeletes;

    /**
     * La tabla asociada al modelo.
     */
    protected $table = 'establecimientos';

    /**
     * Los atributos que son asignables en masa.
     */
    protected $fillable = [
        'cliente_id',
        'nombre_establecimiento',
        'tipo_establecimiento',
        'direccion_completa_establecimiento',
        'lat',
        'lng',
        'colonia',
        'municipio',
        'estado',
        'codigo_postal',
        'telefono_establecimiento',
        'correo_establecimiento',
        'rfc_establecimiento',
        'razon_social_establecimiento',
        'direccion_fiscal_establecimiento',
        'verificacion_establecimiento',
        'grado_confianza',
        'cantidad_reportes',
        'tipos_pago_establecimiento',
        'facturacion_establecimiento',
        'horarios_establecimiento',
        'menu_establecimiento',
        'categoria_id',
        'valoracion_promedio',
        'total_resenas',
        'imagen_portada',
        'galeria_imagenes',
        'activo',
        'fecha_verificacion',
    ];

    /**
     * Los atributos que deben ser casteados a tipos nativos.
     */
    protected $casts = [
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
        'verificacion_establecimiento' => 'boolean',
        'grado_confianza' => 'integer',
        'cantidad_reportes' => 'integer',
        'tipos_pago_establecimiento' => 'array',
        'facturacion_establecimiento' => 'boolean',
        'horarios_establecimiento' => 'array',
        'menu_establecimiento' => 'array',
        'valoracion_promedio' => 'decimal:2',
        'total_resenas' => 'integer',
        'galeria_imagenes' => 'array',
        'activo' => 'boolean',
        'fecha_verificacion' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relación con el cliente propietario del establecimiento.
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /**
     * Relación con la categoría del establecimiento.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    /**
     * Relación con las reseñas del establecimiento.
     */
    public function resenas(): HasMany
    {
        return $this->hasMany(Resena::class, 'establecimiento_id');
    }

    /**
     * Scope para filtrar solo establecimientos activos.
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para filtrar por tipo de establecimiento.
     */
    public function scopePorTipo($query, string $tipo)
    {
        return $query->where('tipo_establecimiento', $tipo);
    }

    /**
     * Scope para filtrar establecimientos verificados.
     */
    public function scopeVerificados($query)
    {
        return $query->where('verificacion_establecimiento', true);
    }

    /**
     * Scope para filtrar por categoría.
     */
    public function scopePorCategoria($query, int $categoriaId)
    {
        return $query->where('categoria_id', $categoriaId);
    }

    /**
     * Scope para filtrar por ubicación (municipio o estado).
     */
    public function scopePorUbicacion($query, ?string $municipio = null, ?string $estado = null)
    {
        if ($municipio) {
            $query->where('municipio', 'like', "%{$municipio}%");
        }
        
        if ($estado) {
            $query->where('estado', 'like', "%{$estado}%");
        }
        
        return $query;
    }

    /**
     * Scope para buscar establecimientos cercanos a una coordenada.
     * Usa la fórmula de Haversine para calcular distancia.
     */
    public function scopeCercanos($query, float $lat, float $lng, float $radioKm = 5)
    {
        $query->selectRaw(
            "*, (6371 * acos(cos(radians(?)) * cos(radians(lat)) * cos(radians(lng) - radians(?)) + sin(radians(?)) * sin(radians(lat)))) AS distancia",
            [$lat, $lng, $lat]
        )
        ->whereNotNull('lat')
        ->whereNotNull('lng')
        ->havingRaw('distancia <= ?', [$radioKm])
        ->orderBy('distancia');

        return $query;
    }

    /**
     * Scope para ordenar por valoración.
     */
    public function scopeMejorValorados($query)
    {
        return $query->orderByDesc('valoracion_promedio')
                    ->orderByDesc('total_resenas');
    }

    /**
     * Scope para filtrar por grado de confianza mínimo.
     */
    public function scopeConfiables($query, int $gradoMinimo = 50)
    {
        return $query->where('grado_confianza', '>=', $gradoMinimo);
    }

    /**
     * Verifica si el establecimiento es formal.
     */
    public function esFormal(): bool
    {
        return $this->tipo_establecimiento === 'formal';
    }

    /**
     * Verifica si el establecimiento es informal.
     */
    public function esInformal(): bool
    {
        return $this->tipo_establecimiento === 'informal';
    }

    /**
     * Verifica si el establecimiento está verificado.
     */
    public function estaVerificado(): bool
    {
        return $this->verificacion_establecimiento === true;
    }

    /**
     * Verifica si el establecimiento tiene ubicación geográfica.
     */
    public function tieneUbicacion(): bool
    {
        return !is_null($this->lat) && !is_null($this->lng);
    }

    /**
     * Verifica si el establecimiento ofrece facturación.
     */
    public function ofreceFacturacion(): bool
    {
        return $this->facturacion_establecimiento === true;
    }

    /**
     * Obtiene el nivel de confianza como texto.
     */
    public function nivelConfianza(): string
    {
        if ($this->grado_confianza >= 80) return 'Muy confiable';
        if ($this->grado_confianza >= 60) return 'Confiable';
        if ($this->grado_confianza >= 40) return 'Moderado';
        if ($this->grado_confianza >= 20) return 'Bajo';
        return 'Muy bajo';
    }

    /**
     * Verifica si acepta un método de pago específico.
     */
    public function aceptaPago(string $metodoPago): bool
    {
        return in_array($metodoPago, $this->tipos_pago_establecimiento ?? []);
    }

    /**
     * Obtiene la URL completa de la imagen de portada.
     */
    public function urlImagenPortada(): ?string
    {
        if (!$this->imagen_portada) {
            return null;
        }

        // Si ya es una URL completa, devolverla tal cual
        if (filter_var($this->imagen_portada, FILTER_VALIDATE_URL)) {
            return $this->imagen_portada;
        }

        // Si es una ruta relativa, construir URL
        return asset('storage/' . $this->imagen_portada);
    }

    /**
     * Incrementa el contador de reseñas y recalcula la valoración promedio.
     */
    public function actualizarValoracion(float $nuevaValoracion): void
    {
        $totalActual = $this->total_resenas;
        $promedioActual = $this->valoracion_promedio;

        $nuevoTotal = $totalActual + 1;
        $nuevoPromedio = (($promedioActual * $totalActual) + $nuevaValoracion) / $nuevoTotal;

        $this->update([
            'valoracion_promedio' => round($nuevoPromedio, 2),
            'total_resenas' => $nuevoTotal,
        ]);
    }
} 