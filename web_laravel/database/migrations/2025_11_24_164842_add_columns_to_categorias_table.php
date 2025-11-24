<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            // Agregar nuevas columnas
            if (!Schema::hasColumn('categorias', 'nombre')) {
                $table->string('nombre')->after('id')->nullable();
            }
            if (!Schema::hasColumn('categorias', 'tipo_establecimiento')) {
                $table->string('tipo_establecimiento')->after('nombre')->nullable();
            }
            if (!Schema::hasColumn('categorias', 'descripcion')) {
                $table->text('descripcion')->after('tipo_establecimiento')->nullable();
            }
            if (!Schema::hasColumn('categorias', 'activo')) {
                $table->boolean('activo')->default(true)->after('descripcion');
            }
        });

        // Migrar datos de nombre_categoria a nombre
        DB::table('categorias')->update([
            'nombre' => DB::raw('nombre_categoria'),
            'descripcion' => DB::raw('descripcion_categoria'),
        ]);

        // Ahora podemos eliminar las columnas antiguas
        Schema::table('categorias', function (Blueprint $table) {
            if (Schema::hasColumn('categorias', 'nombre_categoria')) {
                $table->dropColumn('nombre_categoria');
            }
            if (Schema::hasColumn('categorias', 'descripcion_categoria')) {
                $table->dropColumn('descripcion_categoria');
            }
        });

        // Hacer que 'nombre' no sea nullable después de migrar los datos
        Schema::table('categorias', function (Blueprint $table) {
            $table->string('nombre')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            // Recrear columnas antiguas
            if (!Schema::hasColumn('categorias', 'nombre_categoria')) {
                $table->string('nombre_categoria')->nullable();
            }
            if (!Schema::hasColumn('categorias', 'descripcion_categoria')) {
                $table->text('descripcion_categoria')->nullable();
            }
        });

        // Migrar datos de vuelta
        DB::table('categorias')->update([
            'nombre_categoria' => DB::raw('nombre'),
            'descripcion_categoria' => DB::raw('descripcion'),
        ]);

        // Eliminar nuevas columnas
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn(['nombre', 'tipo_establecimiento', 'descripcion', 'activo']);
        });
    }
};