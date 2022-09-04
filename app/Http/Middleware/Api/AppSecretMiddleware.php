<?php
namespace App\Http\Middleware\Api;
use App\Traits\ResponseUtilsTrait;
use Closure;
use App\Services\Logger\ReqLog\RequestLogger;

class AppSecretMiddleware
{
    use ResponseUtilsTrait;

    public function handle($request, Closure $next, $guard = null)
    {

        if(
            empty($secretKey = $request->header('secret-key')) &&  empty($secretKey = $_SERVER["APP_SECRET_KEY"])){
            return  $this->sendError(trans('apiMessages.auth.appSecretRequiredMessage'), config('responseCode.unauthorized'), config('responseCode.secretkeyFail'));
        }

        if($secretKey !== env('APP_SECRET_KEY')){
	        return  $this->sendError(trans('apiMessages.auth.appSecretFailMessage'), config('responseCode.unauthorized'), config('responseCode.secretkeyFail'));
        }
        return $next($request);
    }
}
