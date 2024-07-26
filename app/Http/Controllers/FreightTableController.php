<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UploadFreightCsvRequest;
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

    public function uploadCSV(UploadFreightCsvRequest $request)
    {
        try {
            $filePaths = [];
            foreach ($request->file('csv_file') as $file) {
                $filePath = $file->store('temp');
                $filePaths[] = storage_path("app/$filePath");
            }

            ProcessFreightTableCsv::dispatch($filePaths);

            return response()->json(['message' => 'Os arquivos estão sendo processados'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao processar os arquivos CSV'], 500);
        }
    }
}
