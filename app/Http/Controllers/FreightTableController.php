<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use League\Csv\Exception;

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
            $csv = Reader::createFromPath($request->file('csv_file')->getRealPath(), 'r');
            $csv->setHeaderOffset(0);
            $csv->setDelimiter(',');

            $records = $csv->getRecords();

            $secondRecord = null;
            foreach ($records as $index => $record) {
                if ($index == 1) {
                    $secondRecord = $record;
                    break;
                }
            }

            if ($secondRecord === null) {
                return response()->json(['error' => 'No second record found in the CSV file'], 400);
            }

            return response()->json($secondRecord, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
