<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Jobs\ProcessFreightTableCsv;
use Exception;

class FreightTableController extends Controller
{
    public function uploadCSV(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:50240',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $filePath = $request->file('csv_file')->store('temp');

            ProcessFreightTableCsv::dispatch(storage_path("app/$filePath"));

            return response()->json(['message' => 'File is being processed'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
