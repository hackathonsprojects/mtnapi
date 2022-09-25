<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompteResource;
use App\Models\Compte;
use Illuminate\Http\Request;
use App\Http\Requests\CompteStoreRequest;

class CompteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comptes = Compte::all();
        if($comptes->count() == 0) return "Aucun compte enregistré";
        else return CompteResource::collection($comptes);
    }


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
            if (!Auth::attempt($request->only(['numero']))){
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
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompteStoreRequest $request)
    {
        try {
            $compte = Compte::create([
                'user_id' => $request->user_id,
                'montant' => $request->montant,
                'has_momo' => $request->has_momo,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Compte créé avec succès',
            ],200);
        } catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $compte = Compte::find($id);
        if($compte == null) return "Compte inexistante";
        else return new CompteResource($compte);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function edit(Compte $compte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $compte = Compte::find($id);
        if($compte == null) return "Compte inexistant";
        else {
            try {
                $compte->update($request->all());
                return new CompteResource($compte);
            }catch (QueryException $e){
                return $e->getMessage();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $compte = Compte::find($id);
        if($compte == null) return "Suppression annulée, Compte inexistant";
        else {
            $compte->delete();
            return "Suppression du compte $compte->id éffectuée";
        }
    }
}
