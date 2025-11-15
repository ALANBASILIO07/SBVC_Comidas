<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Cliente
 * 
 * Representa un establecimiento de comida registrado en la plataforma.
 * Contiene información comercial, fiscal y operativa del negocio.
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
        'nombre_negocio',
        'tipo_negocio',
        'formalidad',
        'tipo_cuenta',
        'telefono',
        'rfc',
        'razon_social',
        'direccion_fiscal',
        'ofrece_facturacion',
        'direccion_completa',
        'ciudad',
        'estado',
        'codigo_postal',
        'metodos_pago',
        'horarios',
        'cierra_dias_festivos',
        'activo',
        'verificado',
    ];

    /**
     * Los atributos que deben ser casteados a tipos nativos.
     */
    protected $casts = [
        'metodos_pago' => 'array',
        'horarios' => 'array',
        'ofrece_facturacion' => 'boolean',
        'cierra_dias_festivos' => 'boolean',
        'activo' => 'boolean',
        'verificado' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relación con el usuario propietario del negocio.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope para filtrar solo clientes activos.
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para filtrar por ciudad.
     */
    public function scopePorCiudad($query, string $ciudad)
    {
        return $query->where('ciudad', 'like', "%{$ciudad}%");
    }

    /**
     * Scope para filtrar por tipo de negocio.
     */
    public function scopePorTipo($query, string $tipo)
    {
        return $query->where('tipo_negocio', $tipo);
    }

    /**
     * Verifica si el negocio es premium.
     */
    public function esPremium(): bool
    {
        return $this->tipo_cuenta === 'premium';
    }

    /**
     * Verifica si el negocio es formal.
     */
    public function esFormal(): bool
    {
        return $this->formalidad === 'formal';
    }
}