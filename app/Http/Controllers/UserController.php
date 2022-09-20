<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $users = User::all();
        if ($users->count() == 0)
            return response()->json([
                'status' => false,
                'message' => 'Aucun utilisateur enregistré',
            ], 200);
        else return UserResource::collection($users);
    }

    public function store(UserStoreRequest $request)
    {
        try {
            $user = User::create([
                'nom' => $request->nom,
                'prenoms' => $request->prenoms,
                'numero' => $request->numero,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Utilisateur créé avec succès',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ],200);
        } catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return UserResource
     */
    public function show($id)
    {
        $result = User::find($id);
        if($result == null)
        return response()->json([
            'status' => false,
            'message' => 'Utilisateur inexistant',
        ], 200);
        else return new UserResource($result);
    }

    /**
     * @param \App\Http\Requests\UserUpdateRequest $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            $user->update($request->all());
            return new UserResource($user);
        }catch (QueryException $e){
            return $e->getMessage();
        }
    }

    /**
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if($user == null)
            return response()->json([
            'status' => false,
            'message' => "Suppression impossible"
            ], 500);
        else {
            $user->delete();
            return response()->json([
                'status' => true,
                'message' => "Suppression de l'utilisateur $user->id éffectuée."
            ], 200);
        }
    }
}
