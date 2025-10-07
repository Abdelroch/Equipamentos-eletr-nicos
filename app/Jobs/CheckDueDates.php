<?php
namespace App\Jobs;

use App\Models\Tax;
use App\Notifications\DueDateNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class CheckDueDates implements ShouldQueue
{
    use Queueable;

    public function handle()
    {
        $tomorrow = \Carbon\Carbon::tomorrow();

        // Verificar impostos pendentes
        $taxes = Tax::whereDate('due_date', $tomorrow)->where('status', 'Pendente')->get();
        foreach ($taxes as $tax) {
            Notification::send(\App\Models\User::all(), new DueDateNotification('Imposto', $tax->tax_type, $tax->due_date));
        }
    }
}
