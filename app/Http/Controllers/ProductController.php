<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Listar todos os produtos cadastrados.
     */
    public function listAll()
    {
        try {
            $products = Product::where('deleted_at', null)->orderBy('name', 'asc')->paginate(10);
            return response()->json($products);

        } catch (QueryException $e) {
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500); 
        }
    }

    /**
     * Adicionar um produto no banco de dados.
     */
    public function register(Request $request)
    {
        DB::beginTransaction();

        try {
            $validatedData = Validator::make($request->all(), [
                'name' => 'required|string|min:4|max:255',
                'description' => 'nullable|string|min:4',
                'price' => 'required|numeric|decimal:0,2|min:0.50',
                'quantity' => 'nullable|integer|min:1',
                'image_url' => 'nullable|string'
            ]);

            if ($validatedData->fails()) {
                return response()->json(['message' => $validatedData->errors()->first()], 422);
            }

            $validatedData = $validatedData->validated();

            Product::create($validatedData);

            DB::commit();

            return response()->json(['message' => 'Product created successfully!'], 201);

        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500); 
        }
    }

    /**
     * Detalhar um produto.
     */
    public function show(string $id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return response()->json(['message' => 'Product not found.'], 404);
            }
            return response()->json($product);

        } catch (QueryException $e) {
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500); 
        }
    }

    /**
     * Editar um produto.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json(['message' => 'Product not found.'], 404);
            }

            $validatedData = Validator::make($request->all(), [
                'name' => 'string|min:4|max:255',
                'description' => 'nullable|string|min:4',
                'price' => 'numeric|decimal:0,2|min:0.50',
                'quantity' => 'nullable|integer|min:1',
                'rating' => 'nullable|decimal:0,1|between:0.0,5.0',
                'image_url' => 'nullable|string'
            ]);

            if ($validatedData->fails()) {
                return response()->json(['message' => $validatedData->errors()->first()], 422);
            }

            $validatedData = $validatedData->validated();

            $product->update($validatedData);

            DB::commit();

            return response()->json(['message' => 'Product updated successfully!'], 200);

        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500); 
        }
    }

    /**
     * Excluir um produto.
     */
    public function delete(string $id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return response()->json(['message' => 'Product not found.'], 404);
            }

            $product->delete();

            return response()->json(['message' => 'Product deleted successfully!'], 200);

        } catch (QueryException $e) {
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500); 
        }
    }

    /**
     * Restaurar um produto excluído.
     */
    public function restore(string $id)
    {
        try {
            $product = Product::withTrashed()->find($id);
            if (!$product) {
                return response()->json(['message' => 'Product not found.'], 404);
            }
            
            $product->restore();
            
            return response()->json(['message' => 'Product restored successfully!'], 200);

        } catch (QueryException $e) {
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500); 
        }
    }
}
