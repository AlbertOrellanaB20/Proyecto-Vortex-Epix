<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\FacturacionController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\BitacoraController;
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

    // Dashboard: todos los roles (módulo de Bryan Steve)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Configuración: solo Administrador (módulo de Bryan Steve)
    Route::middleware('rol:Administrador')->group(function () {
        Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
        Route::put('/configuracion', [ConfiguracionController::class, 'update'])->name('configuracion.update');
    });

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
    | MÓDULO: Clientes + Proveedores  →  Edgar (edgar/clientes)
    *----------------------------------------------------------------*/
    // Clientes Frecuentes: Administrador, Supervisor
    Route::middleware('rol:Supervisor')->group(function () {
        Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
        Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
        Route::post('/clientes/puntos', [ClienteController::class, 'agregarPuntos'])->name('clientes.puntos');
        Route::put('/clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update');
        Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    });
    // Proveedores: Administrador, Inventario
    Route::middleware('rol:Inventario')->group(function () {
        Route::get('/proveedores', [ProveedorController::class, 'index'])->name('proveedores.index');
        Route::post('/proveedores', [ProveedorController::class, 'store'])->name('proveedores.store');
        Route::put('/proveedores/{id}', [ProveedorController::class, 'update'])->name('proveedores.update');
        Route::delete('/proveedores/{id}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy');
    });

    /*----------------------------------------------------------------
    | Otros módulos (de los demás compañeros) — vistas por ahora
    *----------------------------------------------------------------*/
    /*----------------------------------------------------------------
    | MÓDULO: Seguridad + Logs  →  Danilo (danilo/seguridad)
    *----------------------------------------------------------------*/
    // Usuarios / Empleados: Administrador, Supervisor
    Route::middleware('rol:Supervisor')->group(function () {
        Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
        Route::post('/empleados', [EmpleadoController::class, 'store'])->name('empleados.store');
        Route::put('/empleados/{id}', [EmpleadoController::class, 'update'])->name('empleados.update');
        Route::delete('/empleados/{id}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');
    });
    // Bitácora / Logs: solo Administrador
    Route::middleware('rol:Administrador')->group(function () {
        Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');
    });

    /*----------------------------------------------------------------
    | MÓDULO: Facturación + Reportes  →  Eduardo (eduardo/reportes)
    | Acceso: Administrador, Supervisor
    *----------------------------------------------------------------*/
    Route::middleware('rol:Supervisor')->group(function () {
        Route::get('/facturacion', [FacturacionController::class, 'index'])->name('facturacion.index');
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/excel', [ReporteController::class, 'exportarExcel'])->name('reportes.excel');
        Route::get('/reportes/pdf', [ReporteController::class, 'exportarPdf'])->name('reportes.pdf');
    });
});
