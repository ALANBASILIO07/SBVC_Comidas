<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriasSeeder extends Seeder
{
    public function run()
    {
        $categorias = [
            // Restaurante
            ['nombre' => 'Comida Mexicana', 'tipo_establecimiento' => 'Restaurante'],
            ['nombre' => 'Comida China', 'tipo_establecimiento' => 'Restaurante'],
            ['nombre' => 'Comida Italiana', 'tipo_establecimiento' => 'Restaurante'],
            ['nombre' => 'Comida Japonesa', 'tipo_establecimiento' => 'Restaurante'],
            ['nombre' => 'Comida Argentina', 'tipo_establecimiento' => 'Restaurante'],
            ['nombre' => 'Comida Mediterránea', 'tipo_establecimiento' => 'Restaurante'],
            ['nombre' => 'Comida Vegetariana', 'tipo_establecimiento' => 'Restaurante'],
            ['nombre' => 'Comida Vegana', 'tipo_establecimiento' => 'Restaurante'],
            ['nombre' => 'Mariscos', 'tipo_establecimiento' => 'Restaurante'],
            ['nombre' => 'Carnes y Parrillas', 'tipo_establecimiento' => 'Restaurante'],
            ['nombre' => 'Buffet', 'tipo_establecimiento' => 'Restaurante'],
            ['nombre' => 'Comida Rápida', 'tipo_establecimiento' => 'Restaurante'],
            
            // Cafetería
            ['nombre' => 'Café Artesanal', 'tipo_establecimiento' => 'Cafetería'],
            ['nombre' => 'Café Moderno', 'tipo_establecimiento' => 'Cafetería'],
            ['nombre' => 'Cafetería Tradicional', 'tipo_establecimiento' => 'Cafetería'],
            ['nombre' => 'Café y Postres', 'tipo_establecimiento' => 'Cafetería'],
            ['nombre' => 'Té y Café', 'tipo_establecimiento' => 'Cafetería'],
            
            // Food Truck
            ['nombre' => 'Tacos y Antojitos', 'tipo_establecimiento' => 'Food Truck'],
            ['nombre' => 'Hamburguesas', 'tipo_establecimiento' => 'Food Truck'],
            ['nombre' => 'Hot Dogs', 'tipo_establecimiento' => 'Food Truck'],
            ['nombre' => 'Pizza', 'tipo_establecimiento' => 'Food Truck'],
            ['nombre' => 'Postres Móviles', 'tipo_establecimiento' => 'Food Truck'],
            ['nombre' => 'Comida Internacional', 'tipo_establecimiento' => 'Food Truck'],
            
            // Panadería
            ['nombre' => 'Panadería Tradicional', 'tipo_establecimiento' => 'Panadería'],
            ['nombre' => 'Panadería Artesanal', 'tipo_establecimiento' => 'Panadería'],
            ['nombre' => 'Repostería Fina', 'tipo_establecimiento' => 'Panadería'],
            ['nombre' => 'Panadería Francesa', 'tipo_establecimiento' => 'Panadería'],
            ['nombre' => 'Pastelería', 'tipo_establecimiento' => 'Panadería'],
            
            // Bar
            ['nombre' => 'Bar de Cervezas', 'tipo_establecimiento' => 'Bar'],
            ['nombre' => 'Cocteles y Mixología', 'tipo_establecimiento' => 'Bar'],
            ['nombre' => 'Bar Deportivo', 'tipo_establecimiento' => 'Bar'],
            ['nombre' => 'Pub', 'tipo_establecimiento' => 'Bar'],
            ['nombre' => 'Bar Karaoke', 'tipo_establecimiento' => 'Bar'],
            ['nombre' => 'Bar Lounge', 'tipo_establecimiento' => 'Bar'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}