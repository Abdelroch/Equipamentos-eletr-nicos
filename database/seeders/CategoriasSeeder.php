<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasSeeder extends Seeder
{
        public function run(): void
    {
        $categorias = [
            // Computadores
            ['nome' => 'Laptops',              'slug' => 'laptops',              'icone' => 'fa fa-laptop'],
            ['nome' => 'Computadores Desktop', 'slug' => 'desktops',             'icone' => 'fa fa-desktop'],
            ['nome' => 'Monitores',            'slug' => 'monitors',             'icone' => 'fa fa-television'],
            ['nome' => 'All-in-One',           'slug' => 'all-in-one',           'icone' => 'fa fa-desktop'],

            // Telefones & Tablets
            ['nome' => 'Smartphones',          'slug' => 'smartphones',          'icone' => 'fa fa-mobile'],
            ['nome' => 'Tablets',              'slug' => 'tablets',              'icone' => 'fa fa-tablet'],
            ['nome' => 'Smartwatches',         'slug' => 'smartwatches',         'icone' => 'fa fa-clock-o'],

            // Periféricos
            ['nome' => 'Teclados',             'slug' => 'teclados',             'icone' => 'fa fa-keyboard-o'],
            ['nome' => 'Ratos',                'slug' => 'ratos',                'icone' => 'fa fa-dot-circle-o'],
            ['nome' => 'Headphones',           'slug' => 'headphones',           'icone' => 'fa fa-headphones'],
            ['nome' => 'Colunas',              'slug' => 'colunas',              'icone' => 'fa fa-volume-up'],
            ['nome' => 'Webcams',              'slug' => 'webcams',              'icone' => 'fa fa-video-camera'],
            ['nome' => 'Microfones',           'slug' => 'microfones',           'icone' => 'fa fa-microphone'],

            // Armazenamento
            ['nome' => 'Discos Rígidos (HDD)', 'slug' => 'hdd',                  'icone' => 'fa fa-hdd-o'],
            ['nome' => 'SSDs',                 'slug' => 'ssd',                  'icone' => 'fa fa-hdd-o'],
            ['nome' => 'Pen Drives',           'slug' => 'pen-drives',           'icone' => 'fa fa-usb'],
            ['nome' => 'Cartões de Memória',   'slug' => 'cartoes-memoria',      'icone' => 'fa fa-credit-card'],

            // Redes
            ['nome' => 'Routers',              'slug' => 'routers',              'icone' => 'fa fa-wifi'],
            ['nome' => 'Switches',             'slug' => 'switches',             'icone' => 'fa fa-exchange'],
            ['nome' => 'Modems',               'slug' => 'modems',               'icone' => 'fa fa-plug'],

            // Impressão
            ['nome' => 'Impressoras',          'slug' => 'impressoras',          'icone' => 'fa fa-print'],
            ['nome' => 'Scanners',             'slug' => 'scanners',             'icone' => 'fa fa-file-image-o'],
            ['nome' => 'Toners & Tinteiros',   'slug' => 'consumiveis-impressao','icone' => 'fa fa-paint-brush'],

            // Componentes
            ['nome' => 'Processadores (CPU)',   'slug' => 'cpu',                  'icone' => 'fa fa-microchip'],
            ['nome' => 'Placas Gráficas (GPU)', 'slug' => 'gpu',                  'icone' => 'fa fa-gamepad'],
            ['nome' => 'RAM',                   'slug' => 'ram',                  'icone' => 'fa fa-bars'],
            ['nome' => 'Placas-Mãe',           'slug' => 'placas-mae',           'icone' => 'fa fa-microchip'],
            ['nome' => 'Fontes de Alimentação', 'slug' => 'fontes',               'icone' => 'fa fa-bolt'],
            ['nome' => 'Caixas (Cases)',        'slug' => 'cases',                'icone' => 'fa fa-cube'],
            ['nome' => 'Coolers & Ventilação',  'slug' => 'coolers',              'icone' => 'fa fa-snowflake-o'],

            // Foto & Vídeo
            ['nome' => 'Câmeras Fotográficas', 'slug' => 'cameras',              'icone' => 'fa fa-camera'],
            ['nome' => 'Câmeras de Segurança', 'slug' => 'cameras-seguranca',    'icone' => 'fa fa-video-camera'],
            ['nome' => 'Drones',               'slug' => 'drones',               'icone' => 'fa fa-paper-plane'],

            // Gaming
            ['nome' => 'Consolas',             'slug' => 'consolas',             'icone' => 'fa fa-gamepad'],
            ['nome' => 'Jogos',                'slug' => 'jogos',                'icone' => 'fa fa-gamepad'],
            ['nome' => 'Cadeiras Gaming',      'slug' => 'cadeiras-gaming',      'icone' => 'fa fa-chair'],

            // Energia
            ['nome' => 'UPS / No-break',       'slug' => 'ups',                  'icone' => 'fa fa-battery-full'],
            ['nome' => 'Carregadores',         'slug' => 'carregadores',         'icone' => 'fa fa-plug'],
            ['nome' => 'Power Banks',          'slug' => 'power-banks',          'icone' => 'fa fa-battery-three-quarters'],
            ['nome' => 'Painéis Solares',      'slug' => 'paineis-solares',      'icone' => 'fa fa-sun-o'],

            // Reparação (específico do vosso negócio)
            ['nome' => 'Carcaças',             'slug' => 'carcacas',             'icone' => 'fa fa-shield'],
            ['nome' => 'Ecrãs & Displays',     'slug' => 'ecras',                'icone' => 'fa fa-mobile'],
            ['nome' => 'Baterias',             'slug' => 'baterias',             'icone' => 'fa fa-battery-half'],
            ['nome' => 'Cabos & Adaptadores',  'slug' => 'cabos',                'icone' => 'fa fa-exchange'],
            ['nome' => 'Ferramentas Técnicas', 'slug' => 'ferramentas',          'icone' => 'fa fa-wrench'],

            // Escritório
            ['nome' => 'Projetores',           'slug' => 'projetores',           'icone' => 'fa fa-film'],
            ['nome' => 'Telefones Fixos',      'slug' => 'telefones-fixos',      'icone' => 'fa fa-phone'],
            ['nome' => 'Acessórios Escritório','slug' => 'acessorios-escritorio','icone' => 'fa fa-briefcase'],
        ];

        foreach ($categorias as $cat) {
            DB::table('categorias')->updateOrInsert(
                ['slug' => $cat['slug']],
                array_merge($cat, [
                    'activa'     => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
