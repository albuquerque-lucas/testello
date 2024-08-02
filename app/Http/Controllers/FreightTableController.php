<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UploadFreightCsvRequest;
use App\Jobs\ProcessFreightTableCsv;
use App\Models\FreightTable;
use Illuminate\Support\Facades\DB;
use Exception;

class FreightTableController extends Controller
{
    public function index(Request $request)
    {
        try {
            $searchParams = $request->only([
                'branch_id',
                'customer_id',
                'from_postcode',
                'to_postcode',
                'from_weight',
                'to_weight',
                'cost',
                'name',
                'order'
            ]);
            $sortOrder = $searchParams['order'] ?? 'desc';
            $freightTables = FreightTable::search($searchParams, $sortOrder);
    
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

    public function store(Request $request)
    {
        try {
            $freightTable = FreightTable::create($request->all());
            return response()->json($freightTable, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao criar o registro da tabela de frete'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $freightTable = FreightTable::findOrFail($id);
            $freightTable->update($request->all());
            return response()->json($freightTable);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar o registro da tabela de frete'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $freightTable = FreightTable::findOrFail($id);
            $freightTable->delete();
    
            return response()->json(['message' => 'Registro da tabela de frete deletado com sucesso'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Registro não encontrado'], 404);
        } catch (Exception $e) {
            \Log::error('Erro ao deletar o registro da tabela de frete: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao deletar o registro da tabela de frete'], 500);
        }
    }
    

    public function bulkDelete()
    {
        try {
            FreightTable::truncate();
            return response()->json(['message' => 'Todos os registros da tabela de frete foram deletados com sucesso'], 200);
        } catch (Exception $e) {
            \Log::error('Erro ao deletar todos os registros da tabela de frete: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao deletar todos os registros da tabela de frete'], 500);
        }
    }
}
