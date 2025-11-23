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
        Schema::table('clientes', function (Blueprint $table) {
            // Eliminar columnas antiguas si existen
            if (Schema::hasColumn('clientes', 'nombre_negocio')) {
                $table->dropColumn('nombre_negocio');
            }
            if (Schema::hasColumn('clientes', 'tipo_negocio')) {
                $table->dropColumn('tipo_negocio');
            }
            if (Schema::hasColumn('clientes', 'tipo_cuenta')) {
                $table->dropColumn('tipo_cuenta');
            }
            if (Schema::hasColumn('clientes', 'formalidad')) {
                $table->dropColumn('formalidad');
            }
            if (Schema::hasColumn('clientes', 'direccion_fiscal')) {
                $table->dropColumn('direccion_fiscal');
            }
            if (Schema::hasColumn('clientes', 'direccion_completa')) {
                $table->dropColumn('direccion_completa');
            }
            if (Schema::hasColumn('clientes', 'ciudad')) {
                $table->dropColumn('ciudad');
            }
            if (Schema::hasColumn('clientes', 'estado')) {
                $table->dropColumn('estado');
            }
            if (Schema::hasColumn('clientes', 'codigo_postal')) {
                $table->dropColumn('codigo_postal');
            }
            if (Schema::hasColumn('clientes', 'metodos_pago')) {
                $table->dropColumn('metodos_pago');
            }
            if (Schema::hasColumn('clientes', 'horarios')) {
                $table->dropColumn('horarios');
            }
            if (Schema::hasColumn('clientes', 'abre_dias_festivos')) {
                $table->dropColumn('abre_dias_festivos');
            }
            if (Schema::hasColumn('clientes', 'ofrece_facturacion')) {
                $table->dropColumn('ofrece_facturacion');
            }
            if (Schema::hasColumn('clientes', 'rfc')) {
                $table->dropColumn('rfc');
            }
            if (Schema::hasColumn('clientes', 'razon_social')) {
                $table->dropColumn('razon_social');
            }

            // Agregar columnas nuevas según el modelo Cliente
            if (!Schema::hasColumn('clientes', 'nombre_titular')) {
                $table->string('nombre_titular')->after('user_id');
            }
            if (!Schema::hasColumn('clientes', 'email_contacto')) {
                $table->string('email_contacto')->after('nombre_titular');
            }
            if (!Schema::hasColumn('clientes', 'plan')) {
                $table->string('plan')->default('basico')->after('telefono');
            }
            if (!Schema::hasColumn('clientes', 'fecha_inicio_suscripcion')) {
                $table->timestamp('fecha_inicio_suscripcion')->nullable()->after('plan');
            }
            if (!Schema::hasColumn('clientes', 'fecha_fin_suscripcion')) {
                $table->timestamp('fecha_fin_suscripcion')->nullable()->after('fecha_inicio_suscripcion');
            }
            if (!Schema::hasColumn('clientes', 'suscripcion_activa')) {
                $table->boolean('suscripcion_activa')->default(true)->after('fecha_fin_suscripcion');
            }
            if (!Schema::hasColumn('clientes', 'rfc_titular')) {
                $table->string('rfc_titular', 13)->nullable()->after('suscripcion_activa');
            }
            if (!Schema::hasColumn('clientes', 'razon_social_titular')) {
                $table->string('razon_social_titular')->nullable()->after('rfc_titular');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Revertir cambios
            $table->dropColumn([
                'nombre_titular',
                'email_contacto',
                'plan',
                'fecha_inicio_suscripcion',
                'fecha_fin_suscripcion',
                'suscripcion_activa',
                'rfc_titular',
                'razon_social_titular'
            ]);
        });
    }
};