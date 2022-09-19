<?php
    use Bmatovu\MtnMomo\Products\Collection;
    use Bmatovu\MtnMomo\Products\Disbursement;
    use Bmatovu\MtnMomo\Exceptions\CollectionRequestException;
    
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

try {
    $collection = new Collection();
    $disbursement = new Disbursement();
    //$momoTransactionId ="36d12644-0231-4aa1-8bf5-aa8217e63bdb";
    $partyId = "2250547896325";//"2250547896321"; "46733123453"
    $amount=100;
    //$momoTransactionId = $collection->requestToPay('4e74c8b4-a7a5-4c7a-8b7a-59a118673c58', '22505643', 100);
    //print($momoTransactionId);

    //$col = $collection->getTransactionStatus($momoTransactionId);
    //dd($col);

    $cmpt = $collection->getAccountBalance();
    dd($cmpt);
    //$info = $collection->getAccountHolderBasicInfo($partyId);
    //$activ = $collection->isActive($partyId);

    $tranid=$disbursement->transfer("transactionId", $partyId, $amount);
    $st=$disbursement->getTransactionStatus($tranid);
    dd($st);


} catch(CollectionRequestException $e) {
    do {
        printf("\n\r%s:%d %s (%d) [%s]\n\r", 
            $e->getFile(), $e->getLine(), $e->getMessage(), $e->getCode(), get_class($e));
    } while($e = $e->getPrevious());
}
});
