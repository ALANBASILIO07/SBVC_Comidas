<?php
/*
* Nombre de la clase         : 2025_11_19_024010_create_establecimientos_table.php
* Descripción de la clase    : Migración para crear la tabla de establecimientos con todos sus campos
*                               incluyendo geolocalización, menú, horarios y calificaciones.
* Fecha de creación          : 19/11/2024
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
    public function up(): void
    {
        Schema::create('establecimientos', function (Blueprint $table) {
            $table->id();

            // Quien creo el establecimiento (CLIENTE)
            $table->foreignId('cliente_id')
                  ->constrained('clientes')
                  ->onDelete('cascade');

            // Datos basicos
            $table->string('nombre_establecimiento');
            $table->string('tipo_establecimiento')->default('Restaurante');

            // Ubicacion y geolocalizacion
            $table->text('direccion_completa_establecimiento');
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->string('colonia')->nullable();
            $table->string('municipio')->nullable();
            $table->string('estado')->nullable();
            $table->string('codigo_postal', 5)->nullable();

            // Contacto
            $table->string('telefono_establecimiento', 20)->nullable();
            $table->string('correo_establecimiento')->nullable();

            // Datos fiscales (opcionales)
            $table->string('rfc_establecimiento', 13)->nullable();
            $table->string('razon_social_establecimiento')->nullable();
            $table->text('direccion_fiscal_establecimiento')->nullable();

            // Confianza y verificacion comunitaria
            $table->boolean('verificacion_establecimiento')->default(false);
            $table->unsignedTinyInteger('grado_confianza')->default(50);
            $table->unsignedInteger('cantidad_reportes')->default(0);

            // Servicios y operacion (JSON nullable)
            $table->json('tipos_pago_establecimiento')->nullable();
            $table->boolean('facturacion_establecimiento')->default(false);
            $table->json('horarios_establecimiento')->nullable();
            $table->json('menu_establecimiento')->nullable();

            // Categoria (nullable)
            $table->foreignId('categoria_id')
                  ->nullable()
                  ->constrained('categorias')
                  ->onDelete('set null');

            // Valoraciones
            $table->decimal('valoracion_promedio', 3, 2)->default(0.00);
            $table->unsignedInteger('total_resenas')->default(0);

            // Imagenes
            $table->string('imagen_portada')->nullable();
            $table->json('galeria_imagenes')->nullable();

            // Estado general
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_verificacion')->nullable();

            // Auditoria
            $table->timestamps();
            $table->softDeletes();

            // Indices de rendimiento
            $table->index('cliente_id');
            $table->index('nombre_establecimiento');
            $table->index('tipo_establecimiento');
            $table->index('grado_confianza');
            $table->index('activo');
            $table->index('categoria_id');
            $table->index(['lat', 'lng']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('establecimientos');
    }
};