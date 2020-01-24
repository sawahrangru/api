<?php
Route::prefix('v1')->namespace('Api')->group( function (){
    Route::get('/', 'AuthController@index');
    Route::prefix('auth')->group(function(){
        Route::get('/', 'AuthController@index');
        Route::post('/login', 'AuthController@login');
        Route::post('/register', 'AuthController@register');
        Route::post('/authorize', 'AuthController@authorize');
        Route::get('/token', 'AuthController@token');
    });
    Route::prefix('clients')->group(function(){
        Route::post('/', 'ClientController@store');
    });
});
