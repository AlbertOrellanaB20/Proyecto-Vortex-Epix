<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enmascara datos confidenciales cuando el usuario es Administrador.
        // Uso en Blade:  @conf($cliente->nombre)   ->  el admin ve "••••••"
        \Illuminate\Support\Facades\Blade::directive('conf', function ($exp) {
            return "<?php echo (auth()->check() && auth()->user()->cargo === 'Administrador') ? '••••••' : e($exp); ?>";
        });
    }
}
