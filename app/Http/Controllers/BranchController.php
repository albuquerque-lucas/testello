<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use Exception;

class BranchController extends Controller
{
    public function index()
    {
        try {
            $branches = Branch::all();
            return response()->json($branches);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao buscar os branches'], 500);
        }
    }

    public function show($id)
    {
        try {
            $branch = Branch::findOrFail($id);
            return response()->json($branch);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao buscar o branch'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $branch = Branch::create($request->all());
            return response()->json($branch, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao criar o branch'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $branch = Branch::findOrFail($id);
            $branch->update($request->all());
            return response()->json($branch);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar o branch'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $branch = Branch::findOrFail($id);
            $branch->delete();
            return response()->json(['message' => 'Branch deletado com sucesso']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao deletar o branch'], 500);
        }
    }
}
