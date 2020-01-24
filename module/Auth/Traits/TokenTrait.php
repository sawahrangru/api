<?php
namespace Module\Auth\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Module\Auth\Models\ApiToken;
use Webpatser\Uuid\Uuid;
trait TokenTrait
{

 /**
  * @var array
  */
 public $_responses = [
  'success' => false,
  'code'    => 200,
  'message' => '',
  'data'    => null,
 ];

 /**
  * checkHeader
  *
  * @param  mixed $request
  *
  * @return void
  */
 public function checkHeader(Request $request)
 {
  $header                         = $request->header('Authorization');
  $this->_responses['request_id'] = Uuid::generate()->string;
  if (!$header) {
   $this->_responses['code'] = 401;
   $this->_responses['data'] = 'Missing Authorization';
   http_response_code(401);
   header('Content-Type: application/json');
   echo json_encode($this->_responses);
   exit();

  }

  $bearer = explode('Bearer ', $header);
  if (count($bearer) < 2) {
   $this->_responses['code'] = 401;
   $this->_responses['data'] = 'Missing Bearer Tag';
   http_response_code(401);
   header('Content-Type: application/json');
   echo json_encode($this->_responses);
   exit();
  }
 }

 /**
  * checkToken
  *
  * @param  mixed $request
  *
  * @return void
  */
 public function checkToken(Request $request)
 {
  $header = $request->header('Authorization');
  $token  = explode('Bearer ', $header);
  if (count($token) < 2) {
   $this->_responses['code'] = 401;
   $this->_responses['data'] = 'Missing Bearer Tag';
   http_response_code(401);
   header('Content-Type: application/json');
   echo json_encode($this->_responses);
   exit();
  }
  $tokenDB = ApiToken::where('access_token', $token[1] ?? null)->first();
  if (!$tokenDB) {
   $this->_responses['code'] = 400;
   $this->_responses['data'] = 'Invalid access token';
   http_response_code(401);
   header('Content-Type: application/json');
   echo json_encode($this->_responses);
   exit();
  }
  $created = $tokenDB->created_at;
  $exp     = $tokenDB->created_at->addSeconds($tokenDB->expires_in);
  $now     = Carbon::now();
  if ($now->gt($exp)) {

   $this->_responses['code'] = 401;
   $this->_responses['data'] = 'access token expired';
   http_response_code(401);
   header('Content-Type: application/json');
   echo json_encode($this->_responses);
   exit();
  }

 }
 /**
  * check
  *
  * @param  mixed $request
  *
  * @return void
  */
 public function check(Request $request)
 {
  $this->checkHeader($request);
  $this->checkToken($request);
 }

 /**
  * _data
  *
  * @param  mixed $request
  *
  * @return void
  */
 public function _data($request)
 {

  $header = $request->header('Authorization');
  $token  = explode('Bearer ', $header);
  return ApiToken::where('access_token', $token[1] ?? null)->with(['client', 'client.user'])->first();
 }
}
