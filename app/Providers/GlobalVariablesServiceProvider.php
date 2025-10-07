<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Supplier;
use Illuminate\Support\Facades\Schema;


class GlobalVariablesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
            if (Schema::hasTable('supplier')) {
                $fornecedores = Supplier::all();
                View::share('fornecedores', $fornecedores);
            }

    }
}
