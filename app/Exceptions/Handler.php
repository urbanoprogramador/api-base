<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        /*return $this->sussessResponse("hola",200);*/
        if($request->expectsJson()){
            /*header('Access-Control-Allow-Origin: *');*/
            return $this->ApiException($request,$exception);

        }

        return parent::render($request, $exception);
    }
    protected function ApiException($request, Exception $exception){
        if($exception instanceof ModelNotFoundException ){
            $modelo =strtolower(class_basename( $exception->getModel()));
            return $this->errorResponse("No exite ninguna instancia de {$modelo} con el id expecificado",404);
        }

        if($exception instanceof NotFoundHttpException){
            return $this->errorResponse('No se encontro la url expecificada ',404);
        }

        if($exception instanceof MethodNotAllowedHttpException){
            return $this->errorResponse('El metodo expecificado en la peticion no es valido ',405);
        }
        if($exception instanceof QueryException){
            $codigo=$exception->errorInfo[1];
            if($codigo==1451){
                return $this->errorResponse('no se puede eliminar de forma permanente el recurso porque esta relaiconado con algun otro.',409);
            }
        }

        if(config('app.debug')){
            return parent::render($request, $exception);
        }
        return $this->errorResponse('Falla inesperada. intente luego ',500);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
                    ? $this->errorResponse(['message' => "Usuario no autoriazado"], 401)
                    : redirect()->guest($exception->redirectTo() ?? route('login'));
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        return $this->errorResponse([
            'message' => 'Los datos dados no eran válidos.',
            'errors' => $exception->errors(),
        ], $exception->status);
    }
}
