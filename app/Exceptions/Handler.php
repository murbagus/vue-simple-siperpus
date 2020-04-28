<?php

namespace App\Exceptions;

use App\Http\Controllers\Api\v1\ResponseObject;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $respons_obj = new ResponseObject();
        if ($exception instanceof NotFoundHttpException && ($request->expectsJson() || $request->is('api/*'))) {
            $respons_obj->status = $respons_obj::STATUS_FAIL;
            $respons_obj->kode = $respons_obj::CODE_NOT_FOUND;
            $respons_obj->pesan = [
                'error' => 'Tidak menemukan suber pada URI ini'
            ];
            return response()->json($respons_obj, $exception->getStatusCode());
        } else if ($exception instanceof AuthorizationException && ($request->expectsJson() || $request->is('api/*'))) {
            $respons_obj->status = $respons_obj::STATUS_FAIL;
            $respons_obj->kode = $respons_obj::CODE_FORBIDDEN;
            $respons_obj->pesan = [
                'error' => 'Dilarang mengakses sumber pada URI ini'
            ];
            return response()->json($respons_obj, 403);
        }

        return parent::render($request, $exception);
    }
}
