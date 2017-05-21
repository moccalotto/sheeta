<?php

namespace App\Http\Controllers\Api;

use Validator;
use Exception;
use App\Sheet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SheetsController extends Controller
{
    /**
     * Search for sheets.
     */
    public function search(Request $request)
    {
        return Sheet::where(
            'headline',
            'like',
            preg_replace('/^|$|\s+/u', '%', $request->input('headline'))
        )->orderBy('id', 'asc')
        ->paginate($request->input('page-size', 15));
    }

    /**
     * Get a single sheet.
     */
    public function get(Sheet $sheet)
    {
        if ($sheet->allow_view) {
            return $sheet;
        }

        $this->authorize('view', $sheet);

        return $sheet;
    }

    /**
     * Update a sheet
     */
    public function put(Request $request, Sheet $sheet)
    {
        $validation = [
            'headline' => 'string|max:60',
            'allow_clone' => 'boolean',
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
            $sheet->allow_clone = $request->input('allow_clone', $sheet->allow_clone);
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
            'headline' => 'required|string|max:60',
            'allow_clone' => 'boolean',
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
                'allow_clone' => $request->input('allow_clone', 1),
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
        try {
            $clone = $sheet->createClone($request->user());

            return response()->json($clone, 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Clone Failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
