<?php
namespace App\Console\Commands\TestingJobs;

use App\DfCore\DfBs\Enum\ESImportType;
use App\DfCore\DfBs\Enum\ESIndexTypes;
use App\DfCore\DfBs\Import\Facade\ImportFeedFacade;
use App\DfCore\DfBs\Rules\Builder\FeedOperationDirector;
use App\ElasticSearch\DynamicFeedRepository;
use App\ElasticSearch\ESBol;
use App\ElasticSearch\ESCopyIndex;
use App\Entity\Bolads;
use App\Entity\BoladsPreview;
use App\Entity\Bolfeed;
use App\Entity\Repository\BolAdsPreviewRepository;
use App\Entity\Repository\BolAdsRepository;
use App\Entity\Repository\BolFeedRepository;
use Illuminate\Console\Command;
use Mockery\Exception;
use Wienkit\BolPlazaClient\Requests\BolPlazaUpsertRequest;
use Wienkit\BolPlazaClient\Entities\BolPlazaCancellation;
use Wienkit\BolPlazaClient\Entities\BolPlazaShipmentRequest;
use Wienkit\BolPlazaClient\Entities\BolPlazaTransport;
use Wienkit\BolPlazaClient\Entities\BolPlazaChangeTransportRequest;
use Wienkit\BolPlazaClient\Entities\BolPlazaReturnItemStatusUpdate;
use Wienkit\BolPlazaClient\Entities\BolPlazaRetailerOffer;
use Wienkit\BolPlazaClient\BolPlazaClient;
;
class UpdateBol extends Command
{



    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_bol_products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update bol products';


    /**
     * @var int
     */
    private $fk_bol_id;
    /**
     * @var \Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    private $bol_ads;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {

//        $this->fk_bol_id = 1;
//        $bolads_repository  =new BolAdsRepository(new Bolads());
//        $this->bol_ads = $bolads_repository->getAds($this->fk_bol_id);


        parent::__construct();
    }





    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $boladspreview_repository = new BolAdsPreviewRepository(new BoladsPreview());
        $bol_feed_repository = new BolFeedRepository(new Bolfeed());
        $bol_feed = $bol_feed_repository->getBolFeed($this->fk_bol_id);
        $client = new BolPlazaClient('GKAnbtYuNfUFzqEdElqSuFyZkARWfXCr', 'xHNyrhuTPTuFnXLGyuazHqDeHMayepxZjDDWaMUKWQTGKzmCTSFcpzZoOXCFudHhDONgUnOyaTHKFBnLzBDIkkjXcRbiEaUjKIfpchjzReULcaIkUBmFocjZkepgCZEehiNarMrSpebTwHxXmvXEfciOpVYanJkCyIGxsmnPjbyAaOSnvBwtnRzXbyYxYtxpaROYwMVCiMMockGrsDPpxasSUCkMTxTZVeVyNpzYSmgDWmLFaKWhJZLbWFCJXHxK');
        $client->setTestMode(false);
//        $upsertRequest = new BolPlazaUpsertRequest();
//        $offer = new BolPlazaRetailerOffer();
//        $offer->EAN = '8944688059399';
//        $offer->Condition = 'REASONABLE';
//        $offer->Price = '9';
//        $offer->DeliveryCode = '3-5d';
//        $offer->Publish = 'true';
//        $offer->ReferenceCode = 'Spinner';
//        $offer->QuantityInStock = 1;
//        $offer->Description = 'Spinner GEEL';
//        $offer->Title = 'Spinner Geel';
//        $offer->FulfillmentMethod = 'FBR';
//        $upsertRequest->RetailerOffer = $offer;
//
//
//        try {
//            dd($client->createOffer($upsertRequest));
//        } catch (Exception $e) {
//            dd($e->getMessage());
//        }
//
//
//
//        dd();






        $FeedOperationDirector = new FeedOperationDirector();
        $feed_id = $this->bol_ads->getfeed->id;
        $fk_bol_id = $this->fk_bol_id;
        $rule_products = $FeedOperationDirector->buildBolRules($fk_bol_id,[],0,$feed_id);
        ESCopyIndex::copyIndexWithRules($fk_bol_id,$feed_id,$rule_products,ESIndexTypes::BOL);


        /**
         * Now build the bol ads...
         */
        $es_bol = new ESBol(createEsIndexName($this->fk_bol_id,ESIndexTypes::BOL),DFBUILDER_ES_TYPE);
        $all_products = $es_bol->getAllDocuments(true);
        $preview_ads = array_flip($boladspreview_repository->pluckPreviewAds($fk_bol_id));



        foreach($all_products as $key => $product) {
            $insert_bol_ads['fk_bol_id'] = $fk_bol_id;
            $insert_bol_ads['fk_feed_id'] = $feed_id;
            $insert_bol_ads['fk_bol_ad_id'] = $this->bol_ads->id;
            $insert_bol_ads['ean'] = (isset($product['_source'][$this->bol_ads->ean]) ? $product['_source'][$this->bol_ads->ean] : '' );
            $insert_bol_ads['price'] = (isset($product['_source'][$this->bol_ads->price]) ? $product['_source'][$this->bol_ads->price] : '' );
            $insert_bol_ads['reference_code'] = (isset($product['_source'][$this->bol_ads->reference_code]) ? $product['_source'][$this->bol_ads->reference_code] : '' );
            $insert_bol_ads['title'] = (isset($product['_source'][$this->bol_ads->title]) ? $product['_source'][$this->bol_ads->title] : '' );
            $insert_bol_ads['fullfilment'] = $this->bol_ads->fullfilment;
            $insert_bol_ads['condition'] = $this->bol_ads->condition;
            $insert_bol_ads['delivery_code'] = $this->bol_ads->delivery_code;
            $insert_bol_ads['stock'] = (isset($product['_source'][$this->bol_ads->stock]) ? $product['_source'][$this->bol_ads->stock] : '' );
            $insert_bol_ads['description'] = (isset($product['_source'][$this->bol_ads->description]) ? $product['_source'][$this->bol_ads->description] : '' );
            $boladspreview_repository->updateAdByEan($insert_bol_ads,$insert_bol_ads['ean'],$fk_bol_id);
            if(isset($preview_ads[$insert_bol_ads['ean']])) {
                unset($preview_ads[$insert_bol_ads['ean']]);
                continue;
            }
        }


        $get_bol_ads = $boladspreview_repository->getPreviewAds($fk_bol_id);
        $counter = 0;
        foreach($get_bol_ads as $bol_ads) {

            /**
             * Do a insert
             */


            if(!$bol_ads->in_bol_com) {


                /**
                 * Dit stukje werkt wel
                 * Lijkt aan FulfillmentMethod te liggen en  ReferenceCode.
                 * Kijk naar de condities op https://developers.bol.com/create-and-update/
                 */
//                $offer->EAN =(int) $bol_ads->ean;
//                $offer->Condition = 'NEW';
//                $offer->Price = (float) $bol_ads->price;
//                $offer->DeliveryCode = (string)'24uurs-21';
//                $offer->Publish = 'true';
//                $offer->ReferenceCode =  $bol_ads->reference_code;
//                $offer->QuantityInStock = 3;
//                $offer->UnreservedStock = 1;
//                $offer->Description = (string) $bol_ads->description;
//                $offer->Title = (string) $bol_ads->title;
//                $offer->FulfillmentMethod = 'FBR';
//                $upsertRequest->RetailerOffer = $offer;



                $upsertRequest = new BolPlazaUpsertRequest();
                $offer = new BolPlazaRetailerOffer();
                $offer->EAN = $bol_ads->ean;
                $offer->Condition = $bol_ads->condition;
                $offer->Price = $bol_ads->price;
                $offer->DeliveryCode = $bol_ads->delivery_code;
                $offer->Publish = 'true';
                $offer->ReferenceCode = $bol_ads->reference_code;
                $offer->QuantityInStock = $bol_ads->stock;
                $offer->Description = $bol_ads->description;
                $offer->Title = $bol_ads->title;
                $offer->FulfillmentMethod = $bol_ads->fullfilment;
                $upsertRequest->RetailerOffer = $offer;

                try {
                    var_dump($client->createOffer($upsertRequest));
                } catch (\Exception $e) {
                    var_dump($e->getMessage());
                }


//                try {
//                    if($client->createOffer($upsertRequest)) {
//                        $boladspreview_repository->updatePreview(['in_bol_com'=>true,'failed'=>false,'api_response'=>''],$bol_ads->id);
//                    }
//
//
//                } catch (\Exception $e) {
//                    $boladspreview_repository->updatePreview(['in_bol_com'=>false,'failed'=>true,'api_response'=>$e->getMessage()],$bol_ads->id);
//                }
            } else {

                /**
                 * Do an update!
                 */

//                try {
//                    $bol_client->updateOffer('productname', [
//                        'EAN' => $bol_ads->ean,
//                        'Condition' =>$bol_ads->condition,
//                        'Price' => $bol_ads->price,
//                        'DeliveryCode' =>  $bol_ads->delivery_code,
//                        'QuantityInStock' => $bol_ads->stock,
//                        'Publish' => false,
//                        'ReferenceCode' => $bol_ads->reference_code,
//                        'Description' => $bol_ads->description,
//                        'FulfillmentMethod' => $bol_ads->fullfilment,
//                        'Title' => $bol_ads->title
//                    ]);
//                    $boladspreview_repository->updatePreview(['in_bol_com'=>true,'failed'=>false,'api_response'=>''],$bol_ads->id);
//                } catch (\Exception $e) {
//                    $boladspreview_repository->updatePreview(['failed'=>true,'api_response'=>$e->getMessage()],$bol_ads->id);
//                }

            }
            $counter++;

        }


        /**
         * Send to bol.com
         */

//        try {
//
//
//
//            $bol_client->createOffer($insert_bol_ads['ean'], [
//                'EAN' => $insert_bol_ads['ean'],
//                'Condition' => $insert_bol_ads['condition'],
//                'Price' => $insert_bol_ads['price'],
//                'DeliveryCode' =>  $insert_bol_ads['delivery_code'],
//                'QuantityInStock' => $insert_bol_ads['stock'],
//                'Publish' => false,
//                'ReferenceCode' => $insert_bol_ads['reference_code'],
//                'Description' => $insert_bol_ads['description'],
//                'FulfillmentMethod' => $insert_bol_ads['fullfilment'],
//                'Title' => $insert_bol_ads['title']
//            ]);
//            $this->comment("new...");
//
//
////                } else {
////                    $bol_client->updateOffer($insert_bol_ads['ean'], [
////                        'EAN' => $insert_bol_ads['ean'],
////                        'Condition' => $insert_bol_ads['condition'],
////                        'Price' => $insert_bol_ads['price'],
////                        'DeliveryCode' =>  $insert_bol_ads['delivery_code'],
////                        'QuantityInStock' => $insert_bol_ads['stock'],
////                        'Publish' => false,
////                        'ReferenceCode' => $insert_bol_ads['reference_code'],
////                        'Description' => $insert_bol_ads['description'],
////                        'FulfillmentMethod' => $insert_bol_ads['fullfilment'],
////                        'Title' => $insert_bol_ads['title']
////                    ]);
////                    $this->comment("updating...");
////
////                }
//
//            $failed = false;
//        } catch (\Exception $exception) {
//            $failed = true;
//            $api_msg = $exception->getMessage();
//        }
//        $boladspreview_repository->updateAdByEan(['failed'=>$failed,'api_response'=>$api_msg],$insert_bol_ads['ean'],$fk_bol_id);






        /**
         * Delete this product
         */
//        foreach(array_keys($preview_ads) as $deleted_ean) {
//
//        }
//
//
//        dd();








    }


}
