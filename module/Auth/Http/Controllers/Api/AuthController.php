<?php
namespace Module\Auth\Http\Controllers\Api;

use App\Base\BaseController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Module\Auth\Models\ApiClient;
use Module\Auth\Models\ApiToken;
use Module\Auth\Traits\TokenTrait;

class AuthController extends BaseController
{
 use TokenTrait;
 /**
  * @return mixed
  */
 public function index()
 {
  return $this->response(null, 200);
 }
 /**
  * @param Request $request
  * @return mixed
  */
 public function login(Request $request)
 {
  $valid = Validator::make($request->all(), [
   'email'    => ['required', 'string', 'email', 'max:255'],
   'password' => ['required', 'string', 'min:8'],
  ]);
  if ($valid->fails()) {
   $data = $valid->messages();
   return $this->response($data, 409);
  }

  $findUser = User::where(['email' => $request->email])->first();
  if (!$findUser) {
   return $this->response('invalid email/password', 401);
  }
  if (!Hash::check($request->password, $findUser->password)) {
   return $this->response('invalid email/password', 401);
  }
  if (!$findUser->api_key) {
   $findUser->api_key = Str::random(80);
   $findUser->save();
  }
  return $this->response($findUser, 200);
 }
 /**
  * @param Request $request
  * @return mixed
  */
 public function register(Request $request)
 {
  $valid = Validator::make($request->all(), [
   'name'     => ['required', 'string', 'max:255'],
   'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
   'password' => ['required', 'string', 'min:8', 'confirmed'],
  ]);
  if ($valid->fails()) {
   $data = $valid->messages();
   return $this->response($data, 409);
  }
  $data = User::create([
   'name'     => $request->name,
   'email'    => $request->email,
   'password' => Hash::make($request->password),
   'api_key'  => Str::random(80),
  ]);
  return $this->response($data, 201);
 }
 /**
  * @param Request $request
  * @return mixed
  */
 public function authorize(Request $request)
 {
  $header = $request->header('Authorization');
  if (!$header) {
   return $this->response('Missing Authorization', 401);
  }
  $grant_type = $request->grant_type;
  if (!$grant_type) {
   return $this->response('Missing grant_type', 401);
  }
  if ($grant_type != 'client_credentials') {
   return $this->response('grant_type must client_credentials', 401);
  }

  $basic = explode('Basic ', $header);
  if (count($basic) < 2) {
   return $this->response('Missing Basic Tag', 401);
  }

  $base64 = base64_decode($basic[1]);
  $base64 = explode(':', $base64);
  $key    = isset($base64[0]) ? $base64[0] : null;
  $secret = isset($base64[1]) ? $base64[1] : null;

  $client = ApiClient::where('key', $key)->where('secret', $secret)->first();
  if (!$client) {
   return $this->response('Client Not Found', 404);
  }
  $token = ApiToken::create([
   'client_id'    => $client->id,
   'access_token' => bin2hex(random_bytes(32)),
   'token_type'   => 'Bearer',
   'expires_in'   => config('modules.token_expires'),
   'scope'        => 'resource.WRITE resource.READ',
  ]);
  return $this->response($token, 201);
 }
 /**
  * @param Request $request
  * @return mixed
  */
 public function token(Request $request)
 {
  $this->check($request);
  return $this->_data($request);
 }
}
