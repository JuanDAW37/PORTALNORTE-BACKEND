<?php

use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\CiudadesController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\CpController;
use App\Http\Controllers\DireccioneController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\GestorController;
use App\Http\Controllers\IvaController;
use App\Http\Controllers\MaterialesController;
use App\Http\Controllers\PaisesController;
use App\Http\Controllers\ProvinciasController;
use App\Http\Controllers\ReservasController;
use App\Http\Controllers\TelefonosController;
use App\Http\Controllers\TiposactividadeController;
use App\Http\Controllers\TrabajadoresController;
use App\Http\Controllers\UbicacionesController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\PublicidadeController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * Rutas desprotegidas, una es el registro de gestor,
 * en caso de que no haya ningún gestor en la tabla gestors.
 * verPDF, tiene que estar desprotegida, ya que no permite el envío de encabezados HTTP
 */
Route::post('/registro', [AuthController::class, 'registro']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/contarGestor', [AuthController::class, 'contarGestor']);
Route::get('/verPDF', [FacturaController::class, 'verPDF']);

/**
 * Grupo de miiddleware para controlar peticiones que tendrán obligatoriamente token de autenticación
 */
Route::middleware(['auth:sanctum'])->group(function () {

    /**Ruta para logout */
    Route::get('/logout', [AuthController::class, 'logout']);

    /*Rutas API para Actividades*/
    Route::get('/actividades', [ActividadesController::class, 'index']);
    Route::post('/actividades', [ActividadesController::class, 'store']);
    Route::get('/actividades/{actividad}', [ActividadesController::class, 'show']);
    Route::put('/actividades/{actividad}', [ActividadesController::class, 'update']);
    Route::delete('/actividades/{actividad}', [ActividadesController::class, 'destroy']);
    //Attach y detach a tabla pivote actividades_ubicaciones
    Route::post('/actividades/ubicacion/atach/', [ActividadesController::class, 'attachUbicacion']);
    Route::post('/actividades/ubicacion/detach', [ActividadesController::class, 'detachUbicacion']);
    //Attach y detach a tabla pivote actividades_materiales
    Route::post('/actividades/material/atach', [ActividadesController::class, 'attachMaterial']);
    Route::post('/actividades/material/detach', [ActividadesController::class, 'detachMaterial']);
    //Attach y detach a tabla pivote actividades_guias
    Route::post('/actividades/guia/atach', [ActividadesController::class, 'attachGuia']);
    Route::post('/actividades/guia/detach', [ActividadesController::class, 'detachGuia']);
    Route::get('/filtraActividad', [ActividadesController::class, 'filtraActividad']);
    Route::post('/fotoActividad', [ActividadesController::class, 'fotoActividad']);

    /*Rutas API para Clientes*/
    Route::get('/clientes', [ClientesController::class, 'index']);
    Route::post('/clientes', [ClientesController::class, 'store']);
    Route::get('/clientes/{cliente}', [ClientesController::class, 'show']);
    Route::put('/clientes/{cliente}', [ClientesController::class, 'update']);
    Route::get('/filtraClient', [ClientesController::class, 'filtraClient']);
    Route::get('/bajaClient', [ClientesController::class, 'bajaClient']);
    Route::get('/nifUserCli', [ClientesController::class, 'nifUserCli']);
    Route::post('/fotoCliente', [ClientesController::class, 'fotoCliente']);

    /*Rutas API para Ciudades*/
    Route::get('/ciudades', [CiudadesController::class, 'index']);
    Route::post('/ciudades', [CiudadesController::class, 'store']);
    Route::get('/ciudades/{ciudad}', [CiudadesController::class, 'show']);
    Route::put('/ciudades/{ciudad}', [CiudadesController::class, 'update']);
    Route::delete('/ciudades/{ciudad}', [CiudadesController::class, 'destroy']);
    Route::get('/buscaCiudad', [CiudadesController::class, 'buscaCiudad']);

    /**Rutas API para Códigos postales**/
    Route::get('/cp', [CpController::class, 'index']);
    Route::post('/cp', [CpController::class, 'store']);
    Route::get('/cp/{cp}', [CpController::class, 'show']);
    Route::put('/cp/{cp}', [CpController::class, 'update']);
    Route::delete('/cp/{cp}', [CpController::class, 'destroy']);
    Route::get('/buscaCp', [CpController::class, 'buscaCp']);

    /**Rutas API para Direcciones */
    Route::get('/direccion', [DireccioneController::class, 'index']);
    Route::post('/direccion', [DireccioneController::class, 'store']);
    Route::get('/direccion/{direccion}', [DireccioneController::class, 'show']);
    Route::put('/direccion/{direccion}', [DireccioneController::class, 'update']);
    Route::delete('/direccion/{direccion}', [DireccioneController::class, 'destroy']);
    Route::get('/buscarDireccion', [DireccioneController::class, 'buscarDireccion']);

    /**Rutas API para Email**/
    Route::get('/email', [EmailController::class, 'index']);
    Route::post('/email', [EmailController::class, 'store']);
    Route::get('/email/{email}', [EmailController::class, 'show']);
    Route::put('/email/{email}', [EmailController::class, 'update']);
    Route::delete('/email/{email}', [EmailController::class, 'destroy']);
    Route::get('/buscaEmail', [EmailController::class, 'buscaEmail']);

    /**Rutas API para Empresa**/
    Route::get('/empresa', [EmpresaController::class, 'index']);
    Route::post('/empresa', [EmpresaController::class, 'store']);
    Route::get('/empresa/{empresa}', [EmpresaController::class, 'show']);
    Route::put('/empresa/{empresa}', [EmpresaController::class, 'update']);
    Route::delete('/empresa/{empresa}', [EmpresaController::class, 'destroy']);

    /**Rutas API para Facturas**/
    Route::get('/factura', [FacturaController::class, 'index']);
    Route::post('/factura', [FacturaController::class, 'store']);
    Route::get('/factura/{factura}', [FacturaController::class, 'show']);
    Route::put('/factura/{factura}', [FacturaController::class, 'update']);
    Route::get('/enviaEmail', [FacturaController::class, 'enviaEmail']);
    Route::get('/descargarFactura', [FacturaController::class, 'descargarFactura']);
    Route::get('/filtraFact', [FacturaController::class, 'filtraFact']);
    Route::get('/cogeNumFactura', [FacturaController::class, 'cogeNumFactura']);

    /**Rutas API para Gestor*/
    Route::get('/gestor', [GestorController::class, 'index']);
    Route::post('/gestor', [GestorController::class, 'store']);
    Route::get('/gestor/{gestor}', [GestorController::class, 'show']);
    Route::put('/gestor/{gestor}', [GestorController::class, 'update']);
    Route::delete('/gestor/{gestor}', [GestorController::class, 'destroy']);
    Route::get('/filtraGestor', [GestorController::class, 'filtraGestor']);
    Route::get('/nifUserGest', [GestorController::class, 'nifUserGest']);
    Route::post('/fotoGestor', [GestorController::class, 'fotoGestor']);

    /**Ruta para imágenes */
    Route::post('/public/fotos-gestor', [GestorController::class, 'subeFoto']);

    /**Rutas API para IVA**/
    Route::get('/iva', [IvaController::class, 'index']);
    Route::post('/iva', [IvaController::class, 'store']);
    Route::get('/iva/{iva}', [IvaController::class, 'show']);
    Route::put('/iva/{iva}', [IvaController::class, 'update']);
    Route::delete('/iva/{iva}', [IvaController::class, 'destroy']);

    /**Rutas API para Materiales**/
    Route::get('/material', [MaterialesController::class, 'index']);
    Route::post('/material', [MaterialesController::class, 'store']);
    Route::get('/material/{material}', [MaterialesController::class, 'show']);
    Route::put('/material/{material}', [MaterialesController::class, 'update']);
    Route::delete('/material/{material}', [MaterialesController::class, 'destroy']);
    Route::get('/filtraMat', [MaterialesController::class, 'filtraMat']);

    /**Rutas API para Países**/
    Route::get('/pais', [PaisesController::class, 'index']);
    Route::post('/pais', [PaisesController::class, 'store']);
    Route::get('/pais/{pais}', [PaisesController::class, 'show']);
    Route::put('/pais/{pais}', [PaisesController::class, 'update']);
    Route::delete('/pais/{pais}', [PaisesController::class, 'destroy']);
    Route::get('/buscarPais', [PaisesController::class, 'buscarPais']);

    /**Rutas API para Provincias**/
    Route::get('/provincia', [ProvinciasController::class, 'index']);
    Route::post('/provincia', [ProvinciasController::class, 'store']);
    Route::get('/provincia/{provincia}', [ProvinciasController::class, 'show']);
    Route::put('/provincia/{provincia}', [ProvinciasController::class, 'update']);
    Route::delete('/provincia/{provincia}', [ProvinciasController::class, 'destroy']);
    Route::get('/buscarProvincia', [ProvinciasController::class, 'buscarProvincia']);

    /**Rutas API para Publicidade**/
    Route::get('/publicidad', [PublicidadeController::class, 'index']);
    Route::post('/publicidad', [PublicidadeController::class, 'store']);
    Route::get('/publicidad/{publicidad}', [PublicidadeController::class, 'show']);
    Route::put('/publicidad/{publicidad}', [PublicidadeController::class, 'update']);
    Route::delete('/publicidad/{publicidad}', [PublicidadeController::class, 'destroy']);
    Route::get('/buscaPubli', [PublicidadeController::class, 'buscaPubli']);
    Route::post('/fotoPublicidad', [PublicidadeController::class, 'fotoPublicidad']);

    /**Rutas API para Reservas**/
    Route::get('/reserva', [ReservasController::class, 'index']);
    Route::post('/reserva', [ReservasController::class, 'store']);
    Route::get('/reserva/{reserva}', [ReservasController::class, 'show']);
    Route::put('/reserva/{reserva}', [ReservasController::class, 'update']);
    Route::delete('/reserva/{reserva}', [ReservasController::class, 'destroy']);
    Route::get('/filtraReserv', [ReservasController::class, 'filtraReserv']);
    Route::get('/validarReserva', [ReservasController::class, 'validarReserva']);

    /**Rutas API para Teléfonos**/
    Route::get('/telefono', [TelefonosController::class, 'index']);
    Route::post('/telefono', [TelefonosController::class, 'store']);
    Route::get('/telefono/{telefono}', [TelefonosController::class, 'show']);
    Route::put('/telefono/{telefono}', [TelefonosController::class, 'update']);
    Route::delete('/telefono/{telefono}', [TelefonosController::class, 'destroy']);
    Route::get('/buscaTelefono', [TelefonosController::class, 'buscaTelefono']);

    /*Rutas API para Tipos de Actividades*/
    Route::get('/tipos', [TiposactividadeController::class, 'index']);
    Route::post('/tipos', [TiposactividadeController::class, 'store']);
    Route::get('/tipos/{tipo}', [TiposactividadeController::class, 'show']);
    Route::put('/tipos/{tipo}', [TiposactividadeController::class, 'update']);
    Route::delete('/tipos/{tipo}', [TiposactividadeController::class, 'destroy']);
    Route::get('/filtraTipo', [TiposactividadeController::class, 'filtraTipo']);
    Route::post('/fotoTipo', [TiposactividadeController::class, 'fotoTipo']);

    /**Rutas API para Trabajadores**/
    Route::get('/trabajador', [TrabajadoresController::class, 'index']);
    Route::post('/trabajador', [TrabajadoresController::class, 'store']);
    Route::get('/trabajador/{trabajador}', [TrabajadoresController::class, 'show']);
    Route::put('/trabajador/{trabajador}', [TrabajadoresController::class, 'update']);
    Route::delete('/trabajador/{trabajador}', [TrabajadoresController::class, 'destroy']);
    Route::get('/filtraTrab', [TrabajadoresController::class, 'filtraTrab']);
    Route::get('/loginTrab', [TrabajadoresController::class, 'loginTrab']);
    Route::get('/nifUserTrab', [TrabajadoresController::class, 'nifUserTrab']);
    Route::post('/fotoTrabajador', [TrabajadoresController::class, 'fotoTrabajador']);

    /**Rutas API para Ubicaciones**/
    Route::get('/ubicacion', [UbicacionesController::class, 'index']);
    Route::post('/ubicacion', [UbicacionesController::class, 'store']);
    Route::get('/ubicacion/{ubicacion}', [UbicacionesController::class, 'show']);
    Route::put('/ubicacion/{ubicacion}', [UbicacionesController::class, 'update']);
    Route::delete('/ubicacion/{ubicacion}', [UbicacionesController::class, 'destroy']);
    Route::get('/buscaUbicacion', [UbicacionesController::class, 'buscaUbicacion']);

});

