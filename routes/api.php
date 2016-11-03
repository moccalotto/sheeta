<?php

use Illuminate\Http\Request;

Route::get('/sheets/{id}', 'SheetController@show');
Route::post('/sheets', 'SheetController@store');
