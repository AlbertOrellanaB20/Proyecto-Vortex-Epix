<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas Web - Vortex Epix
|--------------------------------------------------------------------------
| Módulo: Login y autenticación con RBAC
| El campo `cargo` define el rol: Administrador, Cajero, Supervisor, Inventario
*/

// Raíz -> formulario de login
Route::get('/', [AuthController::class, 'login'])->name('login');

// Autenticación
Route::get('/login', [AuthController::class, 'login'])->name('login.show');
Route::post('/login', [AuthController::class, 'attempt'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rutas protegidas (requieren sesión activa)
|--------------------------------------------------------------------------
| 'auth' exige estar logueado. 'rol:...' restringe por cargo.
| El Administrador siempre pasa (definido en VerificarRol).
*/
Route::middleware('auth')->group(function () {

    // Dashboard: accesible para TODOS los roles autenticados (según tabla RBAC del Sprint 2)
    Route::get('/dashboard', fn () => view('dashboard.index'))
        ->name('dashboard');

    // Punto de Venta (POS): Administrador, Cajero, Supervisor
    Route::get('/pos', fn () => view('pos.index'))
        ->middleware('rol:Cajero,Supervisor')
        ->name('pos.index');

    // Productos: Administrador, Supervisor, Inventario
    Route::get('/productos', fn () => view('productos.index'))
        ->middleware('rol:Supervisor,Inventario')
        ->name('productos.index');

    // Inventario: Administrador, Inventario
    Route::get('/inventario', fn () => view('inventario.index'))
        ->middleware('rol:Inventario')
        ->name('inventario.index');

    // Clientes: Administrador, Supervisor
    Route::get('/clientes', fn () => view('clientes.index'))
        ->middleware('rol:Supervisor')
        ->name('clientes.index');

    // Proveedores: Administrador, Inventario
    Route::get('/proveedores', fn () => view('proveedores.index'))
        ->middleware('rol:Inventario')
        ->name('proveedores.index');

    // Empleados: Administrador, Supervisor
    Route::get('/empleados', fn () => view('empleados.index'))
        ->middleware('rol:Supervisor')
        ->name('empleados.index');

    // Facturación: Administrador, Supervisor
    Route::get('/facturacion', fn () => view('facturacion.index'))
        ->middleware('rol:Supervisor')
        ->name('facturacion.index');

    // Reportes: Administrador, Supervisor
    Route::get('/reportes', fn () => view('reportes.index'))
        ->middleware('rol:Supervisor')
        ->name('reportes.index');
});