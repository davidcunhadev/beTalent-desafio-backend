<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Client;
use App\Models\Phone;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ClientsController extends Controller
{
    /**
     * Listar todos os clientes cadastrados com suas devidas informações.
     */
    public function listAll()
    {
        try {
            $clients = Client::with(['addresses', 'phones'])->orderBy('id', 'asc')->paginate(10);
            return response()->json($clients);

        } catch (QueryException $e) {
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500); 
        }
    }

    /**
     * Adicionar um(a) cliente no banco de dados.
     */
    public function register(Request $request)
    {
        DB::beginTransaction();

        try {
            $validatedData = Validator::make($request->all(), [
                'name' => 'required|string|regex:/^[\pL\s]+$/u',
                'cpf' => 'required|string|size:11|regex:/^\d{11}$/',
                'street' => 'required|string',
                'number' => 'required|string|regex:/^\d+$/',
                'complement' => 'nullable|string',
                'city' => 'required|string|regex:/^[\pL\s]+$/u',
                'state' => 'required|string|regex:/^[\pL\s]+$/u',
                'zip_code' => 'required|string|size:8|regex:/^\d{8}$/',
                'phone_number' => 'required|string|regex:/^\d{10,11}$/',
            ]);

            if ($validatedData->fails()) {
                return response()->json(['message' => $validatedData->errors()->first()], 422);
            }

            $validatedData = $validatedData->validated();

            $cpfExists = Client::where('cpf', $request->cpf)->exists();
            if ($cpfExists) {
                return response()->json(['message' => 'There is already a client registered with this CPF.'], 409);
            }

            $client = Client::create([
                'name' => $validatedData['name'],
                'cpf' => $validatedData['cpf']
            ]);

            $address = new Address([
                'street' => $validatedData['street'],
                'number' => $validatedData['number'],
                'complement' => $validatedData['complement'] ?? 'N/A',
                'city' => $validatedData['city'],
                'state' => $validatedData['state'],
                'zip_code' => $validatedData['zip_code'],
            ]);

            $client->addresses()->save($address);

            $phone = new Phone([
                'phone_number' => $validatedData['phone_number']
            ]);

            $client->phones()->save($phone);

            DB::commit();

            return response()->json(['message' => 'Client created successfully!'], 201);
    
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500); 
        }
    }
    
    /**
     * Detalhar um(a) cliente e vendas a ele(a) com possibilidade de filtrar vendas por mes/ano.
     */
    public function show(Request $request, string $id)
    {
        try {
            $client = Client::find($id);
            if (!$client) {
                return response()->json(['message' => 'Client not found.'], 404);
            }

            $month = $request->input('month');
            $year = $request->input('year');

            $clientWithFilteredSales = Client::with(['sales' => function($query) use ($month, $year) {
                if ($month && $year) {
                    $query->whereYear('sale_date', $year)->whereMonth('sale_date', $month)->orderBy('sale_date', 'desc');
                } elseif ($month) {
                    $query->whereMonth('sale_date', $month)->orderBy('sale_date', 'desc');
                } elseif ($year) {
                    $query->whereYear('sale_date', $year)->orderBy('sale_date', 'desc');
                } else {
                    $query->orderBy('sale_date', 'desc');
                }
            }])->find($id);

            return response()->json($clientWithFilteredSales);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500); 
        }
    }

    /**
     * Editar um(a) cliente.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $client = Client::find($id);
            if (!$client) {
                return response()->json(['message' => 'Client not found!'], 404);
            }

            $requestData = $request->only([
                'name', 'cpf', 'street', 'number', 'complement', 
                'city', 'state', 'zip_code', 'phone_number'
            ]);

            if (empty(array_filter($requestData))) {
                return response()->json(['message' => 'At least one field must be provided for update!'], 422);
            }

            $validatedData = Validator::make($request->all(), [
                'name' => 'string|regex:/^[\pL\s]+$/u',
                'cpf' => 'string|size:11|regex:/^\d{11}$/|unique:clients,cpf,' . $client->id,
                'street' => 'string',
                'number' => 'string|regex:/^\d+$/',
                'complement' => 'nullable|string',
                'city' => 'string|regex:/^[\pL\s]+$/u',
                'state' => 'string|regex:/^[\pL\s]+$/u',
                'zip_code' => 'string|size:8|regex:/^\d{8}$/',
                'phone_number' => 'string|size:11|regex:/^\d+$/|unique:phones,phone_number,' . $client->phones->first()->id,                
            ]);

            if ($validatedData->fails()) {
                return response()->json(['message' => $validatedData->errors()->first()], 422);
            }

            $validatedData = $validatedData->validated();

            $client->update(array_filter([
                'name' => $validatedData['name'] ?? $client->name,
                'cpf' => $validatedData['cpf'] ?? $client->cpf
            ], 'strlen'));
    
            $address = $client->addresses->first();
            if ($address) {
                $address->update(array_filter([
                    'street' => $validatedData['street'] ?? $address->street,
                    'number' => $validatedData['number'] ?? $address->number,
                    'complement' => $validatedData['complement'] ?? $address->complement,
                    'city' => $validatedData['city'] ?? $address->city,
                    'state' => $validatedData['state'] ?? $address->state,
                    'zip_code' => $validatedData['zip_code'] ?? $address->zip_code,
                ], 'strlen'));
            }

            $phone = $client->phones->first();
            if ($phone) {
                $phone->update(array_filter([
                    'phone_number' => $validatedData['phone_number'] ?? $phone->number
                ], 'strlen'));
            }

            DB::commit();

            return response()->json(['message' => 'Client updated successfully!'], 200);
    
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500); 
        }
    }

    /**
     * Excluir um(a) cliente e vendas a ele(a).
     */
    public function delete(string $id)
    {
        try {
            $client = Client::find($id);
            if (!$client) {
                return response()->json(['message' => 'Client not found.'], 404);
            }

            $client->delete();
            
            return response()->json(['message' => 'Client deleted successfully!'], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500); 
        }
    }
}
