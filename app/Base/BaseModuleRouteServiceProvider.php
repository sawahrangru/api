<?php
namespace App\Base;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class BaseModuleRouteServiceProvider extends ServiceProvider
{
 /**
  * @var mixed
  */
 public $moduleName;
 /**
  * @var string
  */
 public $routePath = 'routes';
 /**
  * @param $moduleName
  */
 public function setNamespace($moduleName)
 {
  return 'Module\\' . $moduleName . '\Http\Controllers';
 }

 /**
  * @param $moduleName
  */
 public function modulePath($moduleName)
 {
  return base_path('module/' . Str::studly($moduleName));
 }
 public function boot()
 {
  parent::boot();
 }
 public function map()
 {
  $this->mapApiRoutes();

  $this->mapWebRoutes();
 }
 protected function mapWebRoutes()
 {
  Route::middleware('web')
   ->namespace($this->setNamespace($this->moduleName))
   ->group($this->modulePath($this->moduleName) . '/' . $this->routePath . '/web.php');
 }

 protected function mapApiRoutes()
 {
  Route::prefix('api')
   ->middleware('api')
   ->namespace($this->setNamespace($this->moduleName))
   ->group($this->modulePath($this->moduleName) . '/' . $this->routePath . '/api.php');
 }
}
