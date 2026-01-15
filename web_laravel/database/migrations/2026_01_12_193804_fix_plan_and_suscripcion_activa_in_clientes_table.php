<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixPlanAndSuscripcionActivaInClientesTable extends Migration
{
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Cambiar plan para que sea nullable y sin default
            $table->string('plan')->nullable()->default(null)->change();

            // Asegurar que suscripcion_activa sea boolean con default false
            $table->boolean('suscripcion_activa')->default(false)->change();
        });
    }

    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Revertir cambios si es necesario
            $table->string('plan')->default('basico')->change();
            $table->boolean('suscripcion_activa')->default(true)->change();
        });
    }
}