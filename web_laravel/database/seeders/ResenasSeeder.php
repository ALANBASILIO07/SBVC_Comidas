<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resena;
use App\Models\Establecimientos;
use Illuminate\Support\Facades\DB;

class ResenasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los establecimientos
        $establecimientos = Establecimientos::all();

        if ($establecimientos->isEmpty()) {
            $this->command->warn('No hay establecimientos en la base de datos. Crea establecimientos primero.');
            return;
        }

        $this->command->info('Generando reseñas de prueba...');

        // Nombres de clientes ficticios
        $nombres = [
            'María Rodríguez', 'Juan García', 'Ana López', 'Carlos Sánchez',
            'Laura Martínez', 'Pedro Hernández', 'Sofia González', 'Miguel Díaz',
            'Carmen Pérez', 'Luis Torres', 'Isabel Ramírez', 'José Flores',
            'Rosa Morales', 'Francisco Jiménez', 'Patricia Ruiz', 'Antonio Medina',
            'Elena Castro', 'Manuel Ortiz', 'Teresa Silva', 'Javier Romero'
        ];

        // Comentarios según puntuación
        $comentarios = [
            5 => [
                'Excelente servicio y la comida deliciosa. Definitivamente volveré a ordenar.',
                'La pizza estaba perfecta y llegó caliente. Súper recomendado.',
                'Increíble experiencia, el sabor es auténtico y las salsas perfectas.',
                'Mejor de lo esperado. Cinco estrellas merecidas.',
                'Simplemente espectacular. La mejor comida que he probado.',
                'Atención de primera y comida deliciosa. Altamente recomendado.',
            ],
            4 => [
                'Buena experiencia en general. La hamburguesa estaba rica pero las papas llegaron un poco frías.',
                'Muy buen sabor, solo tardaron un poco en entregar.',
                'Comida rica y bien servida. Le faltó un poco de sal a mi gusto.',
                'Recomendable, aunque el precio es un poco elevado.',
                'Buen servicio, la comida llegó caliente y en buen estado.',
            ],
            3 => [
                'La comida está bien pero el tiempo de espera fue muy largo. Tardaron casi una hora.',
                'Regular, esperaba más por el precio que cobran.',
                'No está mal, pero tampoco es algo extraordinario.',
                'Aceptable, aunque he probado mejor en otros lugares.',
                'Cumple, sin más. Nada del otro mundo.',
            ],
            2 => [
                'La comida llegó fría y tardaron demasiado.',
                'No me gustó mucho, el sabor era extraño.',
                'Servicio lento y comida sin mucho sabor.',
                'No lo recomiendo, la calidad no justifica el precio.',
            ],
            1 => [
                'Pésima experiencia. Nunca llegó mi pedido.',
                'Muy mala calidad. No volveré a pedir aquí.',
                'Decepcionante en todos los aspectos.',
                'Lo peor que he probado. No lo recomiendo para nada.',
            ],
        ];

        // Generar entre 5 y 20 reseñas por establecimiento
        foreach ($establecimientos as $establecimiento) {
            $cantidadResenas = rand(5, 20);

            $this->command->info("Generando {$cantidadResenas} reseñas para: {$establecimiento->nombre_establecimiento}");

            for ($i = 0; $i < $cantidadResenas; $i++) {
                // Distribución de puntuaciones (más altas son más comunes)
                $rand = rand(1, 100);
                if ($rand <= 60) {
                    $puntuacion = 5;
                } elseif ($rand <= 80) {
                    $puntuacion = 4;
                } elseif ($rand <= 90) {
                    $puntuacion = 3;
                } elseif ($rand <= 97) {
                    $puntuacion = 2;
                } else {
                    $puntuacion = 1;
                }

                // Seleccionar comentario aleatorio según puntuación
                $comentario = $comentarios[$puntuacion][array_rand($comentarios[$puntuacion])];

                // Fecha aleatoria en los últimos 6 meses
                $diasAtras = rand(1, 180);
                $fechaCreacion = now()->subDays($diasAtras);

                Resena::create([
                    'establecimiento_id' => $establecimiento->id,
                    'cliente_nombre' => $nombres[array_rand($nombres)],
                    'cliente_email' => null, // Opcional
                    'puntuacion' => $puntuacion,
                    'comentario' => $comentario,
                    'verificada' => rand(0, 10) > 2, // 80% verificadas
                    'activa' => true,
                    'created_at' => $fechaCreacion,
                    'updated_at' => $fechaCreacion,
                ]);
            }

            // Actualizar valoración promedio del establecimiento
            $promedioResenas = Resena::where('establecimiento_id', $establecimiento->id)
                ->where('activa', true)
                ->avg('puntuacion');

            $totalResenas = Resena::where('establecimiento_id', $establecimiento->id)
                ->where('activa', true)
                ->count();

            $establecimiento->update([
                'valoracion_promedio' => round($promedioResenas, 2),
                'total_resenas' => $totalResenas,
            ]);
        }

        $totalGeneradas = Resena::count();
        $this->command->info("✓ Se generaron {$totalGeneradas} reseñas exitosamente.");
    }
}
