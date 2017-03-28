<?php

/*
 * Display the welcome screen.
 */
Route::get('/', 'WebController@home');

/*
 * Display a single sheet.
 */
Route::get('{$id}', 'WebController@sheet');
