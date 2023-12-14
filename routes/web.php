<?php

use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//Llamo al controlador HomeController que mediante la función __invoke me deriva a la vista welcome
Route::get('/', HomeController::class);//Al indicarle class, le digo que debe existir un método __invoke en el controlador





