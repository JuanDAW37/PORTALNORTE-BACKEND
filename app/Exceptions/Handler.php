<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Crea una respuesta en caso de que se de una excepción de HttpRequest
     */
    public function render($request, Throwable $exception ){
        if ($exception instanceof ModelNotFoundException){
            $data=[
                'status'=>false,
                'message' => "No existe el recurso solicitado",
                'data'=>404
            ];
            return response()->json($data,404);
        }
        return parent::render($request, $exception);

    }
}
