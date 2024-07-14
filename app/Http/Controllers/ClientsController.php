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
     * Listar todos os clientes cadastrados.
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
                'phone_number' => 'required|string|size:11|regex:/^\d+$/',
            ]);

            if ($validatedData->fails()) {
                return response()->json(['message' => $validatedData->errors()->first()], 422);
            }

            $validatedData = $validatedData->validated();

            $cpfExists = Client::where('cpf', $request->cpf)->exists();
            if ($cpfExists) {
                return response()->json(['message' => 'There is already a client registered with this cpf.'], 409);
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
     * Detalhar um(a) cliente e vendas a ele(a).
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Editar um(a) cliente.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $client = Client::find($id);

            if ($client) {
                $validatedData = Validator::make($request->all(), [
                    'name' => 'string|regex:/^[\pL\s]+$/u',
                    'cpf' => 'string|size:11|regex:/^\d{11}$/|unique:clients,cpf,' . $client->id,
                    'street' => 'string',
                    'number' => 'string|regex:/^\d+$/',
                    'complement' => 'nullable|string',
                    'city' => 'string|regex:/^[\pL\s]+$/u',
                    'state' => 'string|regex:/^[\pL\s]+$/u',
                    'zip_code' => 'string|size:8|regex:/^\d{8}$/',
                    'phone_number' => 'string|size:11|regex:/^\d+$/',
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
            } else {
                return response()->json(['message' => 'Client not found!'], 404);
            }
    
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500); 
        }
    }

    /**
     * Excluir um(a) cliente e vendas a ele(a).
     */
    public function destroy(string $id)
    {
        //
    }
}
