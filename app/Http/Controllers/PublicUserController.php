<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PublicUserController extends Controller
{
    /**
     * Obtém um JWT por meio das credenciais fornecidas.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $validatedUserData = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:8',
            ]);
    
            if ($validatedUserData->fails()) {
                return response()->json(['message' => $validatedUserData->errors()->first()], 422);
            }
    
            $validatedUserData = $validatedUserData->validated();
    
            $credentials = $request->only('email', 'password');
            if ($token = auth()->attempt($credentials)) {
                return $this->respondWithToken($token);
            }
    
            return response()->json(['message' => 'Invalid credentials.'], 401);
    
        } catch (QueryException $e) {
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500); 
        }
    }

    /**
     * Cadastra um usuário no banco de dados.
     */
    public function register(Request $request)
    {
        try {
            $validatedUserData = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:8',
            ]);

            if ($validatedUserData->fails()) {
                return response()->json(['message' => $validatedUserData->errors()->first()], 422);
            }

            $validatedUserData = $validatedUserData->validated();
            $validatedUserData['password'] = Hash::make($validatedUserData['password']);

            $userExists = User::where('email', $request->email)->exists();
            if ($userExists) {
                return response()->json(['message' => 'There is already a user registered with this email.'], 409);
            }

            User::create($validatedUserData);

            return response()->json(['message' => 'User created successfully!'], 201);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500); 
        }
    }

    /**
     * Obtém um array com a estrutura do token.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
