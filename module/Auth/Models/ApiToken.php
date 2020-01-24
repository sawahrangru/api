<?php
namespace Module\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
 /**
  * @var array
  */
 protected $fillable = [
  'client_id',
  'access_token',
  'token_type',
  'expires_in',
  'scope',
 ];

 /**
  * @var array
  */
 protected $hidden = [];
 /**
  * @return mixed
  */
 public function client()
 {
  return $this->belongsTo(ApiClient::class);
 }
}
