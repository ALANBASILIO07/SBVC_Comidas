<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Importa esto

class Banner extends Model
{
    /**
     * Los atributos que se pueden asignar en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'imagen_url',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'establecimiento_id', // <-- Probablemente necesites esto
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_inicio' => 'datetime', // <-- Esto permite usar .format()
        'fecha_fin' => 'datetime',    // <-- Esto permite usar .format()
    ];

    /**
     * Obtiene el establecimiento al que pertenece el banner.
     * (Basado en tu vista que usa $banner->establecimiento->nombre)
     */
    /*public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimiento::class);
    }*/
}