<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nome' => 'Laptops',                       'slug' => 'laptops',        'icone' => 'fa fa-laptop'],
            ['nome' => 'Computadores Desktop',           'slug' => 'desktops',       'icone' => 'fa fa-desktop'],
            ['nome' => 'Monitores',                      'slug' => 'monitors',       'icone' => 'fa fa-television'],
            ['nome' => 'Smartphones',                    'slug' => 'smartphones',    'icone' => 'fa fa-mobile'],
            ['nome' => 'Tablets',                         'slug' => 'tablets',        'icone' => 'fa fa-tablet'],
            ['nome' => 'Smartwatches',                   'slug' => 'smartwatches',   'icone' => 'fa fa-clock-o'],
            ['nome' => 'Impressoras e Scanners',          'slug' => 'impressoras',    'icone' => 'fa fa-print'],
            ['nome' => 'Redes e Networking',              'slug' => 'redes',          'icone' => 'fa fa-wifi'],
            ['nome' => 'Áudio e Som',                     'slug' => 'audio',          'icone' => 'fa fa-volume-up'],
            ['nome' => 'Componentes de PC',               'slug' => 'componentes',    'icone' => 'fa fa-microchip'],
            ['nome' => 'Periféricos',                     'slug' => 'perifericos',    'icone' => 'fa fa-keyboard-o'],
            ['nome' => 'Armazenamento Externo',           'slug' => 'armazenamento',  'icone' => 'fa fa-hdd-o'],
            ['nome' => 'Câmaras e Fotografia',            'slug' => 'cameras',        'icone' => 'fa fa-camera'],
            ['nome' => 'Consolas e Gaming',               'slug' => 'gaming',         'icone' => 'fa fa-gamepad'],
            ['nome' => 'Drones',                          'slug' => 'drones',         'icone' => 'fa fa-paper-plane'],
            ['nome' => 'Carcaças / Para Peças',           'slug' => 'carcacas',       'icone' => 'fa fa-shield'],
            ['nome' => 'Acessórios',                      'slug' => 'acessorios',     'icone' => 'fa fa-plug'],
            ['nome' => 'Baterias e Power Banks',          'slug' => 'baterias',       'icone' => 'fa fa-battery-full'],
            ['nome' => 'Segurança e CCTV',                'slug' => 'seguranca',      'icone' => 'fa fa-video-camera'],
            ['nome' => 'TVs e Projectores',               'slug' => 'tvs',            'icone' => 'fa fa-tv'],
            ['nome' => 'Software e Licenças',             'slug' => 'software',       'icone' => 'fa fa-cd'],
            ['nome' => 'Domótica / Casa Inteligente',     'slug' => 'domotica',       'icone' => 'fa fa-home'],
        ];

        foreach ($categorias as $cat) {
            DB::table('categorias')->updateOrInsert(
                ['slug' => $cat['slug']],
                array_merge($cat, ['activa' => true, 'created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
