<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $categorias = [
        ['nome' => 'Laptops',      'slug' => 'laptops',      'icone' => 'fa fa-laptop'],
        ['nome' => 'Smartphones',  'slug' => 'smartphones',  'icone' => 'fa fa-mobile'],
        ['nome' => 'Monitores',    'slug' => 'monitors',     'icone' => 'fa fa-desktop'],
        ['nome' => 'Carcaças',     'slug' => 'carcacas',     'icone' => 'fa fa-shield'],
        ['nome' => 'Accessórios',  'slug' => 'acessorios',   'icone' => 'fa fa-plug'],
        ['nome' => 'Smartwatches', 'slug' => 'smartwatches', 'icone' => 'fa fa-clock-o'],
    ];

    foreach ($categorias as $cat) {
        DB::table('categorias')->updateOrInsert(
            ['slug' => $cat['slug']],
            array_merge($cat, [
                'created_at' => now(),
                'updated_at' => now(),
            ])
        );
    }
}
}
