<?php
namespace Module\Auth\Http\Controllers\Api;

use App\Base\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Module\Auth\Models\ApiClient;
use Module\Auth\Traits\TokenTrait;
use Spatie\Permission\Models\Role;

class ClientController extends BaseController
{
 use TokenTrait;
 /**
  * @param Request $request
  */
 public function __construct(Request $request)
 {
  $this->check($request);
 }
 /**
  * @return mixed
  */
 public function index()
 {
  $clients = ApiClient::with(['user'])->get();
  return $this->response($clients, 200);
 }

 /**
  * @param Request $request
  * @return mixed
  */
 public function store(Request $request)
 {
  $valid = Validator::make($request->all(), [
   'name' => ['required', 'string', 'max:255', 'unique:api_clients'],
  ]);
  if ($valid->fails()) {
   $data = $valid->messages();
   return $this->response($data, 400);
  }
  $client = ApiClient::create([
   'user_id'     => 1,
   'name'        => $request->name,
   'description' => $request->description,
   'secret'      => bin2hex(random_bytes(32)),
   'key'         => bin2hex(random_bytes(32)),
   'active'      => 1,
  ]);
  $role = Role::create([
   'name' => Str::slug($client->name, '.'),
  ]);
  return $this->response($data = ['client' => $client, 'role' => $role], 200);
 }
}
