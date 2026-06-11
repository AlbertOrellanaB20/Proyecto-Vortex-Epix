<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas Web - Vortex Epix
|--------------------------------------------------------------------------
| El campo `cargo` define el rol: Administrador, Cajero, Supervisor, Inventario.
| El Administrador siempre pasa (definido en VerificarRol).
*/

// Login
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('/login', [AuthController::class, 'login'])->name('login.show');
Route::post('/login', [AuthController::class, 'attempt'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    // Dashboard: todos los roles
    Route::get('/dashboard', fn () => view('dashboard.index'))->name('dashboard');

    /*----------------------------------------------------------------
    | MÓDULO: Punto de Venta (POS) + Escáner  →  Diego (diego/pos)
    | Acceso: Administrador, Cajero, Supervisor
    *----------------------------------------------------------------*/
    Route::middleware('rol:Cajero,Supervisor')->group(function () {
        Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
        Route::get('/pos/buscar', [PosController::class, 'buscar'])->name('pos.buscar');
        Route::post('/pos/cobrar', [PosController::class, 'cobrar'])->name('pos.cobrar');
        Route::get('/pos/comprobante/{id}', [PosController::class, 'comprobante'])->name('pos.comprobante');
    });

    /*----------------------------------------------------------------
    | MÓDULO: Productos + Inventario  →  Alberto (alberto/productos)
    *----------------------------------------------------------------*/
    // Productos: Administrador, Supervisor, Inventario
    Route::middleware('rol:Supervisor,Inventario')->group(function () {
        Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
        Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
        Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');
        Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    });
    // Inventario: Administrador, Inventario
    Route::middleware('rol:Inventario')->group(function () {
        Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
        Route::post('/inventario/reabastecer', [InventarioController::class, 'reabastecer'])->name('inventario.reabastecer');
        Route::get('/inventario/exportar', [InventarioController::class, 'exportar'])->name('inventario.exportar');
    });

    /*----------------------------------------------------------------
    | Otros módulos (de los demás compañeros) — vistas por ahora
    *----------------------------------------------------------------*/
    Route::get('/clientes', fn () => view('clientes.index'))->middleware('rol:Supervisor')->name('clientes.index');
    Route::get('/proveedores', fn () => view('proveedores.index'))->middleware('rol:Inventario')->name('proveedores.index');
    Route::get('/empleados', fn () => view('empleados.index'))->middleware('rol:Supervisor')->name('empleados.index');
    Route::get('/facturacion', fn () => view('facturacion.index'))->middleware('rol:Supervisor')->name('facturacion.index');
    Route::get('/reportes', fn () => view('reportes.index'))->middleware('rol:Supervisor')->name('reportes.index');
});
