<?php
namespace Module\PermissionManager\Http\Controllers\Api;

use App\Base\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Module\Auth\Traits\TokenTrait;
use Spatie\Permission\Models\Role;

class PermissionManagerController extends BaseController
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
  return $this->response(null, 200);
 }
 /**
  * roles
  *
  * @param  mixed $request
  *
  * @return void
  */
 public function roles(Request $request)
 {
  $token = $this->_data($request);
  $roles = Role::get();
  return $this->response($roles, 200);
 }
 /**
  * roleStore
  *
  * @param  mixed $request
  *
  * @return void
  */
 public function roleStore(Request $request)
 {

  $valid = Validator::make($request->all(), [
   'name' => ['required', 'string', 'max:255', 'unique:roles'],
  ]);
  if ($valid->fails()) {
   $data = $valid->messages();
   return $this->response($data, 400);
  }
  $role = Role::create([
   'name' => Str::slug($request->name, '.'),
  ]);
  return $this->response($role, 201);
 }
}
