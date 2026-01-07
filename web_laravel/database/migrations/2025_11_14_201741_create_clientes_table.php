<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     * Crea la tabla 'clientes' con toda la información del titular de la cuenta.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->unique()
                  ->constrained('users')
                  ->onDelete('cascade');
    
            // Datos del titular de la cuenta (la persona o empresa que paga)
            $table->string('nombre_titular');
            $table->string('email_contacto');  // Sin unique - se toma del user
            $table->string('telefono', 20);    // Cambiado a 20 caracteres
    
            // Plan y suscripción
            $table->string('plan')->default('estandar');  // Más flexible que enum
            $table->timestamp('fecha_inicio_suscripcion')->nullable();
            $table->timestamp('fecha_fin_suscripcion')->nullable();
            $table->boolean('suscripcion_activa')->default(true);
    
            // Datos fiscales del TITULAR (no del negocio)
            $table->string('rfc_titular', 13)->nullable();
            $table->string('razon_social_titular')->nullable();
    
            $table->timestamps();
            $table->softDeletes();
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