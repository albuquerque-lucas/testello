<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Jobs\ProcessFreightTableCsv;
use App\Models\FreightTable;
use Exception;

class FreightTableController extends Controller
{
    public function index(Request $request)
    {
        try {
            $freightTables = FreightTable::search($request->all());
            return response()->json($freightTables);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao buscar registros da tabela de frete'], 500);
        }
    }
    
    public function show($id)
    {
        try {
            $freightTable = FreightTable::findOrFail($id);
            return response()->json($freightTable);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao buscar o registro da tabela de frete'], 500);
        }
    }

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

            return response()->json(['message' => 'O arquivo está sendo processado'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao processar o arquivo CSV'], 500);
        }
    }
}
