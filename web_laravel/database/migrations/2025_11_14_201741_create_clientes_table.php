<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     * Crea la tabla 'clientes' con toda la información del negocio.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            
            // Llave foránea (de tu 3er ejemplo)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade'); // Si se borra el usuario, se borra su cliente

            // Información básica del negocio (de tu 1er ejemplo)
            $table->string('nombre_negocio');
            $table->string('tipo_negocio'); // Restaurante, Cafetería, etc.
            $table->enum('formalidad', ['formal', 'informal']);
            $table->enum('tipo_cuenta', ['basica', 'premium'])->default('basica');
            $table->string('telefono', 20);
            
            // Información fiscal (nullable para negocios informales)
            $table->string('rfc', 13)->nullable();
            $table->string('razon_social')->nullable();
            $table->text('direccion_fiscal')->nullable();
            $table->boolean('ofrece_facturacion')->default(false);
            
            // Dirección del establecimiento
            $table->text('direccion_completa');
            $table->string('ciudad');
            $table->string('estado');
            $table->string('codigo_postal', 10);
            
            // Métodos de pago y horarios (JSON)
            $table->json('metodos_pago')->nullable();
            $table->json('horarios')->nullable();
            $table->boolean('cierra_dias_festivos')->default(false);
            
            // Estado del negocio
            $table->boolean('activo')->default(true);
            $table->boolean('verificado')->default(false);

            // Soft Deletes (de tu 2do ejemplo)
            $table->softDeletes();
            
            $table->timestamps();
            
            // Índices para búsquedas
            $table->index('ciudad');
            $table->index('estado');
            $table->index('tipo_negocio');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};