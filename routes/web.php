<?php

/*
 * Display the welcome screen.
 */
Route::get('/', 'WebController@home');


// spa routes:
//
// #/{sheetId} - show sheet
// #/{sheetId}/edit - show sheet in edit mode
// #/{sheetId}/addTable - create a new table for the sheet.
