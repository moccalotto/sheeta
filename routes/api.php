<?php

use Illuminate\Http\Request;

Route::get('/sheets', 'Api\SheetsController@search');
Route::post('/sheets', 'Api\SheetsController@post')->name('create');
Route::get('/sheets/{sheet}', 'Api\SheetsController@get');
Route::put('/sheets/{sheet}', 'Api\SheetsController@put');
Route::post('/sheets/{sheet}/clone', 'Api\SheetsController@clone');
