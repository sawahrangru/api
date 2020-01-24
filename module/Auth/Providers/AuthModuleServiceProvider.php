<?php
namespace Module\Auth\Providers;

use App\Base\BaseModuleServiceProvider;

class AuthModuleServiceProvider extends BaseModuleServiceProvider
{
 /**
  * @var string
  */
 public $moduleName = 'Auth';

 public function register()
 {
  $this->app->register(AuthModuleRouteServiceProvider::class);
 }
}
