<?php
namespace App\Base;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class BaseModuleServiceProvider extends ServiceProvider
{
 /**
  * @var mixed
  */
 public $moduleName;
 /**
  * @var string
  */
 public $migrationPath = "database/migrations";
 /**
  * @var string
  */
 public $configPath = "config";
 /**
  * @var string
  */
 public $resourcePath = "resources";
 /**
  * @param $moduleName
  */
 public function modulePath($moduleName)
 {
  return base_path('module/' . Str::studly($moduleName));
 }
 public function boot()
 {
  $this->registerTranslations();
  //$this->registerConfig();
  $this->registerViews();
  $this->registerFactories();
  $this->loadMigrationsFrom($this->modulePath($this->moduleName) . '/' . $this->migrationPath);
 }
 protected function registerConfig()
 {
  $this->publishes([
   $this->modulePath($this->moduleName) . '/' . $this->configPath . '/config.php' => config_path('$LOWER_NAME$.php'),
  ], 'config');
  $this->mergeConfigFrom(
   $this->modulePath($this->moduleName) . '/' . $this->configPath . '/config.php', strtolower($this->moduleName)
  );
 }
 public function registerViews()
 {
  $viewPath = resource_path('views/modules/' . strtolower($this->moduleName));

  $sourcePath = $this->modulePath($this->moduleName) . '/' . $this->resourcePath . '/views';

  $this->publishes([
   $sourcePath => $viewPath,
  ], ['views', strtolower($this->moduleName) . '-module-views']);

  $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), strtolower($this->moduleName));
 }
 public function registerTranslations()
 {
  $langPath = resource_path('lang/modules/' . strtolower($this->moduleName));

  if (is_dir($langPath)) {
   $this->loadTranslationsFrom($langPath, strtolower($this->moduleName));
  } else {
   $this->loadTranslationsFrom($this->modulePath($this->moduleName) . '/' . $this->resourcePath . '/lang', strtolower($this->moduleName));
  }
 }
 public function registerFactories()
 {}
 public function provides()
 {
  return [];
 }

 /**
  * @return mixed
  */
 private function getPublishableViewPaths(): array
 {
  $paths = [];
  foreach (\Config::get('view.paths') as $path) {
   if (is_dir($path . '/modules/' . strtolower($this->moduleName))) {
    $paths[] = $path . '/modules/' . strtolower($this->moduleName);
   }
  }
  return $paths;
 }
}
