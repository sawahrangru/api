<?php
Route::prefix('v1')->namespace('Api')->group(function () {
 Route::prefix('permission-manager')->group(function () {
  Route::get('/', 'PermissionManagerController@index');
  Route::get('/roles', 'PermissionManagerController@roles');
  Route::post('/roles', 'PermissionManagerController@roleStore');
 });
});
