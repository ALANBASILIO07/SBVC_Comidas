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
            $table->foreignId('user_id')
                  ->unique()->constrained('users')
                  ->onDelete('cascade');
    
            // Datos del titular de la cuenta (la persona o empresa que paga)
            $table->string('nombre_titular');                    // Nombre del cliente real
            $table->string('email_contacto')->unique();          // Email de contacto (puede ser diferente al de login)
            $table->string('telefono', 10);
    
            // Plan y suscripción
            $table->enum('plan', ['basico', 'estandar', 'premium'])->default('estandar');
            $table->timestamp('fecha_inicio_suscripcion')->useCurrent();
            $table->timestamp('fecha_fin_suscripcion')->nullable(); // Para control de vencimiento
            $table->boolean('suscripcion_activa')->default(true); // Se modifica en segundo plano
    
            // Datos fiscales del TITULAR (no del negocio)
            $table->string('rfc_titular', 13)->nullable();
            $table->string('razon_social_titular')->nullable();
    
            $table->softDeletes();
            $table->timestamps();
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