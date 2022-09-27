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
        $user_recever = User::where("numero",$request->numero)->first();
        $compteDebit = Compte::where('user_id','=', $user_recever->id)->first();
        $compteCredit = Compte::where('user_id','=', $request->id_recever)->first();



        // if ($compteDebit->montant < $request->montant) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => "Transaction impossiblte solde débiteur insuffisant"
        //     ], 500);
        // }
        // else{

            try {

                $collection = new Collection();
                $disbursement = new Disbursement();
                //$momoTransactionId ="36d12644-0231-4aa1-8bf5-aa8217e63bdb";
                $partyId = $request->numero;//"2250547896321"; "46733123453"
                $amount=$request->montant;
                //$momoTransactionId = $collection->requestToPay('4e74c8b4-a7a5-4c7a-8b7a-59a118673c58', '22505643', 100);
                //print($momoTransactionId);
                //$col = $collection->getTransactionStatus($momoTransactionId);
                //dd($col);
                // $cmpt = $collection->getAccountBalance();
                // dd($cmpt);
                //$info = $collection->getAccountHolderBasicInfo($partyId);
                //$activ = $collection->isActive($partyId);

                $tranid=$disbursement->transfer("transactionId", $partyId, $amount);
                $st=$disbursement->getTransactionStatus($tranid);

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
        catch(CollectionRequestException $e) {
            do {
                printf("\n\r%s:%d %s (%d) [%s]\n\r",
                    $e->getFile(), $e->getLine(), $e->getMessage(), $e->getCode(), get_class($e));
            } while($e = $e->getPrevious());
        }
        // }

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
