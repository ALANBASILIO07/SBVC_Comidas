<?php
/*
* Nombre de la clase         : 2025_11_14_201741_create_clientes_table.php
* Descripción de la clase    : Migración para crear la tabla de clientes propietarios de establecimientos.
* Fecha de creación          : 14/11/2024
* Elaboró                    : Alan Osvaldo Basilio Delgado
* Fecha de liberación        : 22/11/2024
* Autorizó                   : Maileth Patiño Ensastegui
* Versión                    : 1.0
* Fecha de mantenimiento     :
* Folio de mantenimiento     :
* Tipo de mantenimiento      :
* Descripción del mantenimiento :
* Responsable                :
* Revisor                    :
*/

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