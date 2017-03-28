<?php

namespace App\Http\Controllers;

use App\Sheet;
use Illuminate\Http\Request;

class SheetsController extends Controller
{
    public function get(Sheet $sheet)
    {
        return $sheet;
    }

    public function update(Sheet $sheet, Request $request)
    {
        //
    }

    public function clone(Sheet $sheet, Request $request)
    {
        // create a new instance of this sheet, version 1
    }
}
