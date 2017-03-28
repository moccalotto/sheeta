<?php

use Illuminate\Http\Request;

Route::get('/sheets/{id}', 'SheetsController@get');
Route::get('/sheets', 'SheetsController@search');
