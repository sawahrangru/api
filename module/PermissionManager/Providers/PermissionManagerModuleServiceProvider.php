<?php
namespace Module\PermissionManager\Providers;

use App\Base\BaseModuleServiceProvider;

class PermissionManagerModuleServiceProvider extends BaseModuleServiceProvider
{

 /**
  * @var string
  */
 public $moduleName = 'PermissionManager';

 public function register()
 {
  $this->app->register(PermissionManagerModuleRouteServiceProvider::class);
 }
}
