<?php

namespace App\Http\Controllers\Api;

use Ensure;
use App\Sheet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SheetController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sheet = Sheet::fromArray($request->all());

        $sheet->save();

        return $sheet->toArray();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sheet = Sheet::findOrFail($id);

        if (!$sheet) {
            abort(404);
        }

        return $sheet->toArray();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sheet = Sheet::findOrFail($id);

        $sheet->name = (string) $request->get('name', $sheet->name);
        $sheet->template = (bool) $request->get('template', $sheet->template);

        Ensure::that($sheet->name)->as('name')->isString();
        Ensure::that($sheet->name)->as('name')->isBoolean();

        return collect($sheet)->only(['id', 'name', 'template']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return (array) Sheet::write()->deleteOne(['_id' => $id]);
    }
}
