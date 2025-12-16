<?php
/*
* Nombre de la clase         : 2025_11_19_054755_create_banners_table.php
* Descripción de la clase    : Migración para crear la tabla de banners publicitarios de establecimientos,
*                               gestiona imágenes promocionales con fechas de vigencia.
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
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('establecimiento_id')
                  ->constrained('establecimientos')
                  ->onDelete('cascade');
            
            $table->string('titulo_banner');
            $table->text('descripcion_banner')->nullable();
            $table->string('imagen_banner');
            $table->string('url_destino')->nullable();
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->boolean('activo')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('establecimiento_id');
            $table->index('activo');
            $table->index(['fecha_inicio', 'fecha_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
}; 