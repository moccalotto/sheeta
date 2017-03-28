<?php

use Illuminate\Http\Request;

Route::get('/sheets', 'SheetsController@search');
Route::get('/sheets/{sheet}', 'SheetsController@get');
Route::put('/sheets/{sheet}', 'SheetsController@put');
Route::post('/sheets/{sheet}/clone', 'SheetsController@clone');

