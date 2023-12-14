<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class rutas
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request)
        //URL del Front
        ->header("Access-Control-Allow-Origin", "*")
        //Métodos que a los que se da acceso
        ->header("Access-Control-Allow-Methods", "GET, POST, OPTIONS, PUT, DELETE")
        //Headers de la petición
        ->header("Allow:", "GET, POST, OPTIONS, PUT, DELETE")
        ->header("Access-Control-Allow-Headers", "X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        if($method == "OPTIONS") {
            die();
        }
    }
}
