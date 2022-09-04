<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Traits\ResponseUtilsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class Handler
 * @package App\Exceptions
 */
class Handler extends ExceptionHandler
{
    use ResponseUtilsTrait;

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

    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * @param Request $request
     * @param Exception $exception
     * @return JsonResponse|\Illuminate\Http\Response|Response
     */
    public function render($request, Exception $exception)
    {
        if (env('SHOW_EXCEPTION', false)) {
            return parent::render($request, $exception);
        }

        if ($request->isJson()) {
            return $this->customeApiExceptionHandle($exception);
        }

        if ($exception instanceof AppException) {
            $error = $exception->getMessage();
            $httpStatusCode = $exception->getHttpStatusCode();
            $responseCode = config('responseCode.globalError');

            return $this->sendError($error, $httpStatusCode, $responseCode);
        }


        return parent::render($request, $exception);
    }

    public function customeApiExceptionHandle($exception)
    {
        $error = trans('api/messages.responseFailed');
        $httpStatusCode = config('responseCode.serverError');
        $responseCode = config('responseCode.globalError');

        switch (true) {
            case $exception instanceof ValidationException:
                $error = $exception->errors()[array_key_first($exception->errors())][0];
                $httpStatusCode = config('responseCode.validationFail');
                break;

            case $exception instanceof NotFoundHttpException:
                $error = 'متاسفانه url موردنظر موجود نمی باشد';
                $httpStatusCode = config('responseCode.notFound');
                break;

            case $exception instanceof ModelNotFoundException:
                $error = trans('api/messages.modelNotFound');
                $httpStatusCode = config('responseCode.notFound');
                break;

            case $exception instanceof AppException:
                $error = $exception->getMessage();
                $httpStatusCode = $exception->getHttpStatusCode();
                break;

            case $exception->getCode() == config('responseCode.validationFail'):
                $error = $exception->getMessage();
                $httpStatusCode = config('responseCode.validationFail');
                break;
        }

        return $this->sendError($error, $httpStatusCode, $responseCode);
    }
}
