<?php

namespace App\Http\Controllers\DfCore\Marketplaces;
use App\DfCore\DfBs\Enum\ESIndexTypes;
use App\DfCore\DfBs\Enum\UrlKey;
use App\DfCore\DfBs\Rules\Wizard\ChannelWizard;
use App\ElasticSearch\ESBol;
use App\Entity\Repository\Contract\iBolAds;
use App\Entity\Repository\Contract\iBolAdsPreview;
use App\Entity\Repository\Contract\iBolFeed;
use App\Http\Controllers\Controller;
use BolCom\Client;
use Illuminate\Http\Request;
use Route;
class BolController extends Controller
{


    private $bol_feed;
    private $route_name;
    private $bol_ads;
    private $bol_ads_preview;
    public function __construct(Request $request, iBolFeed $bol_feed, iBolAds $bol_ads, iBolAdsPreview $bol_ads_preview)
    {

        if(php_sapi_name() != 'cli') {
            $this->url_key =  (int) $request->get('url_key');
            $this->route_name = Route::currentRouteName();
        }

        $this->bol_feed = $bol_feed;
        $this->bol_ads = $bol_ads;
        $this->bol_ads_preview = $bol_ads_preview;
    }


    public function bol_settings($feed_id,$fk_bol_id=0)
    {
        $bol_feed  = null;
        $wizard = null;
        $bol_feeds = $this->bol_feed->getCompleteBolFeed(session('store_id'));
        $route_name = $this->route_name;
        if($fk_bol_id > 0) {
            $bol_feed = $this->bol_feed->getBolFeed($fk_bol_id);
            $wizard = ChannelWizard::getNavigation(UrlKey::BOL,['feed_id'=>$feed_id,'bol_id'=>$fk_bol_id]);
        }
        return view('dfcore.bol.bol_settings')->with(compact('route_name','bol_feed','wizard','feed_id','fk_bol_id','bol_feeds'));

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post_bol_feed(Request $request)
    {


        $data = $request->only(['name','public_key','private_key','update_interval','status','fk_feed_id']);
        if((int) $request->get('id') == 0 ) {
            $data['next_update'] = \Carbon\Carbon::now()->tz(DFBULDER_TIMEZONE)->addSecond($data['update_interval']);
        }
        $create_bol = $this->bol_feed->createBolFeed($data,(int) $request->get('id'));

        if((int) $request->get('id') == 0 ) {

            $id = $create_bol->id;
        } else {
            $id = $request->get('id');
        }

        $request->session()->flash('flash_success_noty',trans('messages.bol_lbl_8'));
        return redirect()->route('bol.bol_settings',['feed_id'=>$data['fk_feed_id'],'fk_adwords_feed_id'=>$id]);
    }


    /**
     * @param $id
     * @param $feed_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove_bol_feed($id,$feed_id)
    {
        $this->bol_feed->removeBolFeed($id);
        return redirect()->route('bol.bol_settings',['feed_id'=>$feed_id]);
    }

    private function alterFieldNames($field_names)
    {
        $new_fields = [];
        foreach($field_names as $fields){
            $new_fields[$fields]  = $fields;
        }
        return $new_fields;
    }

    /**
     * @param $feed_id
     * @param $fk_bol_id
     * @return $this
     */
    public function build_bol_ad($feed_id,$fk_bol_id)
    {
        $route_name = $this->route_name;
        $bol_ad = $this->bol_ads->getAds($fk_bol_id,false);
        $index_name = createEsIndexName($feed_id,ESIndexTypes::TMP);
        $es_feed = new ESBol($index_name,DFBUILDER_ES_TYPE);
        $field_names = $this->alterFieldNames($es_feed->getEsFields($feed_id));
        $wizard = ChannelWizard::getNavigation(UrlKey::BOL,['feed_id'=>$feed_id,'bol_id'=>$fk_bol_id]);
        return view('dfcore.bol.build_bol_ad')->with(compact('wizard','feed_id','fk_bol_id','route_name','field_names','bol_ad'));
    }


    /**
     * @param Request $request
     */
    public function post_bol_ad(Request $request)
    {
        $data = $request->only(['ean','condition','price','title','stock','description','fullfilment','fk_bol_id','fk_feed_id','delivery_code','reference_code']);
        $has_ad = $this->bol_ads->getAds($data['fk_bol_id']);
        if(count($has_ad) == 0 ) {
            $this->bol_ads->createAds($data);
        } else {
            $this->bol_ads->createAds($data,$data['fk_bol_id'],'fk_bol_id');
        }

    }


}
