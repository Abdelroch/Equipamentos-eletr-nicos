<?php

namespace App\Console\Commands;

use App\Services\OrderNegotiationService;
use Illuminate\Console\Command;

class ReleaseExpiredOrderReservations extends Command
{
    /**
     * php artisan orders:release-expired
     */
    protected $signature = 'orders:release-expired';

    protected $description = 'Liberta o stock reservado de encomendas aceites há mais de 24h sem comprovativo submetido.';

    public function handle(): int
    {
        $count = OrderNegotiationService::releaseExpiredReservations();

        if ($count > 0) {
            $this->info("{$count} reserva(s) expirada(s) e libertada(s) de volta ao stock.");
        } else {
            $this->info('Nenhuma reserva expirada encontrada.');
        }

        return self::SUCCESS;
    }
}
