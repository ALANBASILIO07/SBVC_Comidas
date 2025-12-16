<?php
/*
* Nombre de la clase         : 2025_12_16_153127_create_personal_access_tokens_table.php
* Descripción de la clase    : Migración de Laravel Sanctum para crear la tabla de tokens de acceso personal,
*                               utilizada para autenticación API de la aplicación móvil.
* Fecha de creación          : 16/12/2024
* Elaboró                    : Sistema Laravel Sanctum
* Fecha de liberación        : 16/12/2024
* Autorizó                   : Alan Osvaldo Basilio Delgado
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
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->text('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
