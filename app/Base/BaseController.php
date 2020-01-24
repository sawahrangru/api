<?php
namespace App\Base;

use App\Base\BaseApiCode;
use Illuminate\Routing\Controller;
use Webpatser\Uuid\Uuid;

class BaseController extends Controller
{

 /**
  * response
  *
  * @param  mixed $data
  * @param  mixed $code
  *
  * @return void
  */
 public function response($data = null, $code = 200)
 {
  $response = [
   'success'    => true,
   'code'       => $code,
   'request_id' => Uuid::generate()->string,
   'message'    => BaseApiCode::$messages[$code],
   'data'       => $data,
  ];
  if ($code == 200) {
   $response['success'] = true;
  } else {
   $response['success'] = false;
  }
  return response()->json($response, $code);
 }

}
