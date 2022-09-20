<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\AuthLoginRequest;

class AuthController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AuthLoginRequest $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'numero' => 'required|integer',
                    'password' => 'required'
                ]
            );
            if ($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Erreurs de validation',
                    'erreurs' => $validateUser->errors()
                ], 401);
            }
            if (!Auth::attempt($request->only(['numero', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => "Le numero & le mot de passe ne correspondent pas.",
                ], 401);
            }

            $user = User::where('numero', $request->numero)->first();
            return response()->json([
                'status' => true,
                'message' => 'Utilisateur authentifié',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        }catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request){
        // dd("h");
        auth()->user()->tokens()->delete();

        return [
            'message' => "Utilisateur déconnecté"
        ];
    }
}
