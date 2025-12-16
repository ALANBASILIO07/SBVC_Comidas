<?php
/*
* Nombre de la clase         : 2025_11_19_043113_create_promociones_table.php
* Descripción de la clase    : Migración para crear la tabla de promociones de establecimientos,
*                               gestiona ofertas y descuentos con fechas de vigencia.
* Fecha de creación          : 19/11/2024
* Elaboró                    : Alan Osvaldo Basilio Delgado
* Fecha de liberación        : 23/11/2024
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
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('promociones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('establecimientos_id');
            $table->string('titulo');
            $table->text('descripcion');
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_final');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            // Llave foránea
            $table->foreign('establecimientos_id')
                  ->references('id')
                  ->on('establecimientos')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promociones');
    }
};