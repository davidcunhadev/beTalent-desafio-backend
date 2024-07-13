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
                'name' => 'required|string',
                'cpf' => 'required|string|size:11|regex:/^\d{11}$/',
                'street' => 'required|string',
                'number' => 'required|string|regex:/^\d+$/',
                'complement' => 'nullable|string',
                'city' => 'required|string|regex:/^[A-Za-z\s]+$/',
                'state' => 'required|string|regex:/^[A-Za-z\s]+$/',
                'zip_code' => 'required|string|size:8|regex:/^\d{8}$/',
                'phone_number' => 'required|string|regex:/^\d+$/',
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
        //
    }

    /**
     * Excluir um(a) cliente e vendas a ele(a).
     */
    public function destroy(string $id)
    {
        //
    }
}
