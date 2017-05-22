<?php

/*
 * Display the welcome screen.
 */
Route::get('/', 'WebController@home')->name('home');

Route::group(['prefix' => 'api', 'namespace' => 'Api'], function () {

    Route::get('/sheets', 'SheetsController@search');
    Route::get('/sheets/{sheet}', 'SheetsController@get');

    Route::post('/sheets', 'SheetsController@post')->middleware('can:create,App\Sheet');
    Route::put('/sheets/{sheet}', 'SheetsController@put')->middleware('can:update,sheet');
    Route::post('/sheets/{sheet}/clone', 'SheetsController@clone')->middleware('can:clone,sheet');
});
