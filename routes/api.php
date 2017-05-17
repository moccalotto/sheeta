<?php

use Illuminate\Http\Request;

Route::get('/sheets', 'Api\SheetsController@search');
Route::post('/sheets', 'Api\SheetsController@post')->name('create')->middleware('can:create,App\Sheet');
Route::get('/sheets/{sheet}', 'Api\SheetsController@get')->middleware('can:view,sheet');
Route::put('/sheets/{sheet}', 'Api\SheetsController@put')->middleware('can:update,sheet');
Route::post('/sheets/{sheet}/clone', 'Api\SheetsController@clone')->middleware('can:clone,sheet');
