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
        Schema::table('promociones', function (Blueprint $table) {
            // Campos de imagen y tipo de promoción
            $table->string('imagen')->nullable()->after('descripcion');
            $table->string('tipo_promocion')->nullable()->after('imagen');

            // Campos de valores de descuento
            $table->decimal('valor_descuento', 5, 2)->nullable()->after('tipo_promocion');
            $table->decimal('precio_promocion', 10, 2)->nullable()->after('valor_descuento');

            // Campos de horarios y días
            $table->json('dias_semana')->nullable()->after('fecha_final');
            $table->time('hora_inicio')->nullable()->after('dias_semana');
            $table->time('hora_fin')->nullable()->after('hora_inicio');

            // Términos y condiciones
            $table->text('terminos_condiciones')->nullable()->after('hora_fin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promociones', function (Blueprint $table) {
            $table->dropColumn([
                'imagen',
                'tipo_promocion',
                'valor_descuento',
                'precio_promocion',
                'dias_semana',
                'hora_inicio',
                'hora_fin',
                'terminos_condiciones'
            ]);
        });
    }
};
