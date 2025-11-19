<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('establecimientos', function (Blueprint $table) {
            $table->id();

            // Quién creó el establecimiento (CLIENTE)
            $table->foreignId('cliente_id')
                  ->after('id')
                  ->constrained('clientes')
                  ->onDelete('cascade');

            // =================================================================
            // Datos básicos
            // =================================================================
            $table->string('nombre_establecimiento');
            $table->enum('tipo_establecimiento', ['formal', 'informal'])->default('informal');

            // =================================================================
            // Ubicación y geolocalización (imprescindible para búsquedas cercanas)
            // =================================================================
            $table->text('direccion_completa_establecimiento');
            $table->decimal('lat', 10, 8)->nullable();   // 19.4326077
            $table->decimal('lng', 10, 8)->nullable();   // -99.1332080
            $table->string('colonia')->nullable();
            $table->string('municipio')->nullable();
            $table->string('estado')->nullable();
            $table->string('codigo_postal', 5)->nullable();

            // =================================================================
            // Contacto
            // =================================================================
            $table->string('telefono_establecimiento', 10)->nullable();
            $table->string('correo_establecimiento')->nullable();

            // =================================================================
            // Datos fiscales (solo formales)
            // =================================================================
            $table->string('rfc_establecimiento', 13)->nullable();
            $table->string('razon_social_establecimiento')->nullable();
            $table->text('direccion_fiscal_establecimiento')->nullable();

            // =================================================================
            // Confianza y verificación comunitaria
            // =================================================================
            $table->boolean('verificacion_establecimiento')->default(false);
            $table->unsignedTinyInteger('grado_confianza')->default(0);      // 0-100
            $table->unsignedInteger('cantidad_reportes')->default(0);       // cuántos usuarios lo confirmaron

            // =================================================================
            // Servicios y operación
            // =================================================================
            $table->json('tipos_pago_establecimiento');     // ["efectivo", "tarjeta_credito", "otro:oxxo"]
            $table->boolean('facturacion_establecimiento')->default(false);
            $table->json('horarios_establecimiento')->nullable();
            $table->json('menu_establecimiento')->nullable();

            // =================================================================
            // Categoría y valoraciones
            // =================================================================
            $table->foreignId('categoria_id')
                  ->constrained('categorias')
                  ->onDelete('restrict');

            $table->decimal('valoracion_promedio', 3, 2)->default(0.00);  // 4.82
            $table->unsignedInteger('total_resenas')->default(0);

            // =================================================================
            // Imágenes
            // =================================================================
            $table->string('imagen_portada')->nullable();           // ruta o URL de la foto principal
            $table->json('galeria_imagenes')->nullable();           // ["img1.jpg", "img2.jpg", ...]

            // =================================================================
            // Estado general
            // =================================================================
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_verificacion')->nullable();

            // =================================================================
            // Auditoría
            // =================================================================
            $table->timestamps();
            $table->softDeletes();

            // =================================================================
            // Índices de rendimiento
            // =================================================================
            $table->index('cliente_id');
            $table->index('nombre_establecimiento');
            $table->index('tipo_establecimiento');
            $table->index('grado_confianza');
            $table->index('activo');
            $table->index('categoria_id');
            $table->index(['lat', 'lng']);
            $table->spatialIndex(['lat', 'lng']); // para búsquedas geográficas rápidas
            $table->fullText(['nombre_establecimiento', 'direccion_completa_establecimiento']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('establecimientos');
    }
};