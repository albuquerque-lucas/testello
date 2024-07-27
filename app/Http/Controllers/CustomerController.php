<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Exception;

class CustomerController extends Controller
{
    public function index()
    {
        try {
            $customers = Customer::all();
            return response()->json($customers);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao buscar os clientes'], 500);
        }
    }

    public function show($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            return response()->json($customer);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao buscar o cliente'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $customer = Customer::create($request->all());
            return response()->json($customer, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao criar o cliente'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->update($request->all());
            return response()->json($customer);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar o cliente'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();
            return response()->json(['message' => 'Cliente deletado com sucesso']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao deletar o cliente'], 500);
        }
    }
}
