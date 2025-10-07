<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = 'admin/dashboard';

    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('web')
            ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::middleware(['web', 'auth']) // Adiciona middleware necessário
                ->prefix('admin')             // Prefixo para rotas de admin
                ->name('admin.')              // Namespace opcional para rotas
                ->group(base_path('routes/admin.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    protected function mapWebRoutes()
{
    Route::middleware('web')
         ->namespace($this->namespace) // Adiciona o namespace padrão
         ->group(base_path('routes/web.php'));
}


}
