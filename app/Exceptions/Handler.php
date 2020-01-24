<?php
namespace App\Exceptions;

use App\Base\BaseApiCode;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webpatser\Uuid\Uuid;

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
  * @param  \Exception  $exception
  * @return void
  *
  * @throws \Exception
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
  * @return \Symfony\Component\HttpFoundation\Response
  *
  * @throws \Exception
  */
 public function render($request, Exception $exception)
 {
  $response = [
   'success' => false,
   'code'    => 200,
   'message' => '',
   'data'    => null,
  ];
  $response['request_id'] = Uuid::generate()->string;
  if ($exception instanceof MethodNotAllowedHttpException) {
   $response['code']    = 405;
   $response['message'] = BaseApiCode::$messages[405];
   $response['data']    = $exception->getMessage();
   return response()->json($response, 405);
  }
  if ($exception instanceof QueryException) {
   $response['code']    = 500;
   $response['message'] = BaseApiCode::$messages[500];
   $response['data']    = $exception->getMessage();
   return response()->json($response, 500);
  }
  if ($exception instanceof NotFoundHttpException) {
   $response['code']    = 404;
   $response['message'] = BaseApiCode::$messages[404];
   $response['data']    = $exception->getMessage();
   return response()->json($response, 404);
  }
  if ($exception instanceof FatalThrowableError) {
   $response['code']    = 500;
   $response['message'] = BaseApiCode::$messages[500];
   $response['data']    = $exception->getMessage();
   return response()->json($response, 500);
  }
  if ($exception instanceof RoleAlreadyExists) {
   $response['code']    = 409;
   $response['message'] = BaseApiCode::$messages[409];
   $response['data']    = $exception->getMessage();
   return response()->json($response, 409);
  }
  return parent::render($request, $exception);
 }
}
