<?php

namespace App\Http\Controllers\Api;

use Validator;
use Exception;
use App\Sheet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SheetsController extends Controller
{
    public function search()
    {
        // find sheets by headline and/or user-id
    }

    public function get(Sheet $sheet)
    {
        return $sheet;
    }

    public function put(Request $request, Sheet $sheet)
    {
        if ($request->user()->id !== $sheet->user_id) {
            return response()->json([
                'error' => 'Denied',
                'message' => 'Sheet can only be modified by its owner'
            ], 403);
        }
        $validation = [
            'headline' => 'string|max:255',
            'allow_copy' => 'boolean',
            'tables' => 'array',
            'version' => 'required|int',
        ];

        $validator = Validator::make($request->all(), $validation);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }

        if ($sheet->version !== $request->input('version')) {
            return response()->json([
                'error' => 'Version conflict',
                'message' => sprintf(
                    'Expected version %d, but got version %d',
                    $sheet->version,
                    $request->input('version')
                ),
                'expectedVersion' => $sheet->version,
                'gotVersion' => $request->input('version'),
            ]);
        }

        try {
            $sheet->headline = $request->input('headline', $sheet->headline);
            $sheet->tables = $request->input('tables', $sheet->tables);
            $sheet->version++;

            return response()->json($sheet, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Update Failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function post(Request $request)
    {
        $validation = [
            'headline' => 'required|string|max:255',
            'allow_copy' => 'boolean',
            'tables' => 'array',
            'version' => 'nullable|int|size:1'
        ];

        $validator = Validator::make($request->all(), $validation);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validaton Error',
                'message' => $validator->errors(),
            ], 400);
        }

        try {
            $attributes = [
                'headline' => $request->input('headline'),
                'user_id' => $request->user()->id,
                'allow_copy' => $request->input('allow_copy', 1),
                'version' => 1,
                'tables' => $request->input('tables', []),

            ];

            $sheet = Sheet::forceCreate($attributes);

            return response()->json($sheet, 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Creation Failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function clone(Request $request, Sheet $sheet)
    {
        if (!($sheet->allow_copy || $request->user()->id === $sheet->user_id)) {
            return response()->json([
                'error' => 'Denied',
                'message' => 'Sheet can only be copied by its owner'
            ], 403);
        }

        try {
            $attributes = [
                'headline' => $sheet->headline,
                'user_id' => $request->user()->id,
                'tables' => $sheet->tables,
                'allow_copy' => $sheet->allow_copy,
                'version' => 1,
            ];

            $sheet = Sheet::forceCreate($attributes);

            return response()->json($sheet, 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Clone Failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
