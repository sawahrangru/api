<?php
namespace Module\Auth\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class ApiClient extends Model
{
 use Uuids;
 /**
  * @var mixed
  */
 public $incrementing = false;
 /**
  * @var array
  */
 protected $fillable = [
  'user_id',
  'name',
  'description',
  'secret',
  'key',
  'active',
 ];
 /**
  * @var array
  */
 protected $hidden = ['key', 'secret', 'active'];
 /**
  * @return mixed
  */
 public function user()
 {
  return $this->belongsTo(\App\User::class);
 }
}
