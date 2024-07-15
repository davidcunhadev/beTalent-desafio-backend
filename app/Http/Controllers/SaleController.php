<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    /**
     * Adicionar venda de 1 produto a 1 cliente.
     */
    public function register(Request $request)
    {
        DB::beginTransaction();

        try {
            $validatedData = Validator::make($request->all(), [
                'client_id' => 'required|exists:clients,id',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);
    
            if ($validatedData->fails()) {
                return response()->json(['message' => $validatedData->errors()->first()], 422);
            }
    
            $validatedData = $validatedData->validated();

            $client = Client::find($validatedData['client_id']);
            if (!$client) {
                return response()->json(['message' => 'Client not found.'], 404);
            }

            $product = Product::find($validatedData['product_id']);
            if (!$product) {
                return response()->json(['message' => 'Product not found.'], 404);
            }

            if ($product->quantity < $validatedData['quantity']) {
                return response()->json(['message' => 'The quantity exceeds the stock limit.'], 409);
            }

            $unitPrice = $product->price;
            $totalPrice = $unitPrice * $validatedData['quantity'];
            
            Sale::create([
                'client_id' => $validatedData['client_id'],
                'product_id' => $validatedData['product_id'],
                'quantity' => $validatedData['quantity'],
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'sale_date' => now(),
            ]);

            $product->decrement('quantity', $validatedData['quantity']);

            DB::commit();

            return response()->json(['message' => 'Sale registered successfully!'], 201);

        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500); 
        }
    }
}
