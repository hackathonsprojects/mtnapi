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
    public function login(Request $request)
    {

        try {
            $validateUser = Validator::make($request->all(),
                [
                    'numero' => 'required',
                    // 'password' => 'required'
                ]
            );
            if ($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Erreurs de validation',
                    'erreurs' => $validateUser->errors()
                ], 401);
            }
            // dd($request->only(['numero','password']));

            // if (!Auth::attempt($request->only(['numero','password']))){
            //     return response()->json([
            //         'status' => false,
            //         'message' => "Le numero  ne correspond à aucun compte.",
            //     ], 401);
            // }

            $user = User::where('numero', $request->numero)->first();
            if($user){
            return response()->json([
                'status' => true,
                'message' => 'Utilisateur authentifié',
                'user' => $user,
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        }
        else{
            return response()->json([
                        'status' => false,
                        'message' => "Le numero  ne correspond à aucun compte.",
                    ], 401);
        }
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
