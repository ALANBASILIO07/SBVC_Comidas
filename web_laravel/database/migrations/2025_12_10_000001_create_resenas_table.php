<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resenas', function (Blueprint $table) {
            $table->id();

            // Relación con establecimiento
            $table->foreignId('establecimiento_id')
                  ->constrained('establecimientos')
                  ->onDelete('cascade');

            // Información de la reseña
            $table->string('cliente_nombre');
            $table->string('cliente_email')->nullable();
            $table->unsignedTinyInteger('puntuacion')->comment('1-5 estrellas');
            $table->text('comentario');

            // Metadata
            $table->boolean('verificada')->default(false);
            $table->boolean('activa')->default(true);

            $table->timestamps();

            // Índices
            $table->index(['establecimiento_id', 'puntuacion', 'created_at']);
            $table->index('activa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resenas');
    }
};
