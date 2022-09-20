<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionsResource;
use App\Models\Transactions;
use App\Models\Compte;
use Illuminate\Http\Request;
use App\Http\Requests\TransactionStoreRequest;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transactions::all();
        if($transactions->count() == 0) return "Aucune transaction enregistrée";
        else return TransactionsResource::collection($transactions);
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
    public function store(TransactionStoreRequest $request)
    {
        $compteDebit = Compte::where('user_id','=', $request->id_sender)->first();
        $compteCredit = Compte::where('user_id','=', $request->id_recever)->first();



        if ($compteDebit->montant < $request->montant) {
            return response()->json([
                'status' => false,
                'message' => "Transaction impossiblte solde débiteur insuffisant"
            ], 500);
        }
        else{

            try {

                $transactions = Transactions::create([
                    'id_sender' => $request->id_sender,
                    'id_recever' => $request->id_recever,
                    'montant' => $request->montant
                ]);
                $compteDebit->update(['montant' =>$compteDebit->montant - $request->montant]);
                $compteCredit->update(['montant' => $compteCredit->montant + $request->montant]);
                return response()->json([
                    'status' => true,
                    'message' => 'Transaction éffectuée avec succès',
                ],200);
            } catch (\Throwable $th){
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transactions = Transactions::find($id);
        if($transactions == null) return "Transactions inexistante";
        else return new TransactionsResource($transactions);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function edit(Transactions $transactions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transactions $transactions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transactions $transactions)
    {
        //
    }
}
