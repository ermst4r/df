<?php
/**
 *  This file is part of Dfbuilder.
 *
 *     Dfbuilder is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     Dfbuilder is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with Dfbuilder.  If not, see <http://www.gnu.org/licenses/>
 */

/**
 *  This file is part of Dfbuilder.
 *
 *     Dfbuilder is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     Dfbuilder is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with Dfbuilder.  If not, see <http://www.gnu.org/licenses/>
 */


namespace App\Http\Controllers\DfCore\Channel;

use App\DfCore\DfBs\Channels\ExportChannels\NL\Zanox;
use App\DfCore\DfBs\Enum\ESIndexTypes;
use App\DfCore\DfBs\Enum\UpdateIntervals;
use App\DfCore\DfBs\Enum\UrlKey;
use App\DfCore\DfBs\Import\Mapping\ChannelMapping;
use App\DfCore\DfBs\Import\Mapping\MappingValidator;
use App\DfCore\DfBs\Log\FeedlogFacade;
use App\DfCore\DfBs\Rules\Wizard\ChannelWizard;
use App\ElasticSearch\DynamicFeedRepository;
use App\ElasticSearch\ESChannel;
use App\Entity\Repository\Contract\iChannel;
use App\Entity\Repository\Contract\iChannelCountry;
use App\Entity\Repository\Contract\iChannelCustomMapping;
use App\Entity\Repository\Contract\iChannelFeed;
use App\Entity\Repository\Contract\iChannelFeedMapping;
use App\Entity\Repository\Contract\iChannelMapping;
use App\Entity\Repository\Contract\iChannelType;
use App\Entity\Repository\Contract\iFeed;
use App\Entity\Repository\Contract\iSpreadsheetHeader;
use App\Http\Controllers\Controller;
use App\Jobs\UpdateRules;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Route;

class ChannelController extends Controller
{


    /**
     * @var
     */
    private $es_feed;
    /**
     * @var
     */
    private $feed_id ;

    /**
     * @var
     */
    private $channel_feed;

    /**
     * @var
     */
    private $channel;

    /**
     * @var
     */
    private $channel_country;


    private $channel_mapping;


    /**
     * @var
     */
    private $channel_type;

    /**
     * @var
     */
    private $url_key;

    /**
     * @var
     */
    private $route_name;

    /**
     * @var
     */
    private  $channel_feed_mapping;


    /**
     * @var string
     */
    private $index_name;

    /**
     * @var
     */
    private $spreadsheet_header;

    /**
     * @var
     */
    private $channel_custom_mapping;

    private $feed;


    /**
     * FilterController constructor.
     * @param iFeed $feed
     * @param Request $request
     * @throws \Exception
     */
    public  function __construct(
                                 Request $request ,
                                 iChannelFeed $channel_feed,
                                 iChannel $channel,
                                 iChannelCountry $channel_country,
                                 iChannelType $channel_type ,
                                 iChannelMapping $channel_mapping,
                                 iChannelFeedMapping $channel_feed_mapping,
                                 iSpreadsheetHeader $spreadsheet_header,
                                 iChannelCustomMapping $channel_custom_mapping,
                                iFeed $feed
                                )
    {
        $this->channel_feed = $channel_feed;
        $this->feed = $feed;
        $this->channel = $channel;
        $this->channel_country = $channel_country;
        $this->channel_type = $channel_type;
        $this->channel_mapping = $channel_mapping;
        $this->channel_feed_mapping = $channel_feed_mapping;
        $this->spreadsheet_header = $spreadsheet_header;
        $this->channel_custom_mapping = $channel_custom_mapping;
        if(php_sapi_name() != 'cli') {
            $this->feed_id =  (int) $request->route()->parameter('feed_id');
            $this->url_key =  (int) $request->get('url_key');
            $this->route_name = Route::currentRouteName();
        }

        $this->index_name = createEsIndexName($this->feed_id);
        $this->es_feed = new DynamicFeedRepository( $this->index_name,DFBUILDER_ES_TYPE);
    }




    public function manage_channels()
    {

        $feeds = $this->channel_feed->getCompleteChannelDetails(session('store_id'));
        return view('dfcore.channel.manage_channels')->with(compact('feeds'));
    }

    /**
     * Ajax get field
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ajax_get_field($feed_id,$channel_feed_id,$channel_type_id,$extra_field_counter)
    {

        $fields = $this->es_feed->getEsFields($feed_id);
        $channel_fields_to_map = $this->channel_mapping->getChannelMappings($channel_feed_id.$channel_type_id,false);
        return view('dfcore.channel.ajax_get_field')->with(compact('fields','channel_fields_to_map','extra_field_counter'));
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function start_wizard(Request $request)
    {
        $feed_id = $request->get('feed_id');

        if($feed_id > 0) {
            return redirect()->route('channel.channel_settings',['feed_id'=>$feed_id]);
        }
        $feeds = $this->feed->getFeedByStore(session('store_id'));
        if(count($feeds) == 0 ){
            $request->session()->flash('flash_warning_message',trans('messages.channel_start_lbl6'));
            return redirect()->route('import.selectfeed');
        }
        return view('dfcore.channel.start_wizard')->with(compact('feeds'));
    }

    /**
     *
     * @param $feed_id
     * @return $this
     */
    public function channel_settings($feed_id,$channel_feed_id=0, Request $request)
    {
        $route_name = $this->route_name;
        $feed = $this->feed->getFeed($feed_id);
        $wizard = ChannelWizard::getNavigation($this->url_key,['feed_id'=>$feed_id,'channel_feed_id'=>$channel_feed_id,'channel_type_id'=>$request->get('channel_type_id')]);
        $channel_feed = null;
        $channels_from_feed = $this->channel_feed->getActiveChannelsFromFeed($this->feed_id);
        $types  = null;
        $update_interval = UpdateIntervals::DAILY;
        if($channel_feed_id > 0 ) {
            $channel_feed = $this->channel_feed->getChannelFeed($channel_feed_id);
            $types = $this->channel_type->getChannelTypeByChannel($channel_feed->fk_channel_id);
            $update_interval = $channel_feed->update_interval;
        }


        $channel_countries = $this->channel_country->getCountries();
        return view('dfcore.channel.channel_settings')->with(
            compact('feed_id','channel_countries','channel_feed','wizard','route_name','types','channels_from_feed','channel_feed_id','update_interval','feed'));

    }



    /**
     * FInalize the feed
     * @param $feed_id
     * @param Request $request
     */
    public function finalize($feed_id, $channel_feed_id, $channel_type_id)
    {

        $wizard = ChannelWizard::getNavigation($this->url_key,['feed_id'=>$feed_id,'channel_feed_id'=>$channel_feed_id,'channel_type_id'=>$channel_type_id]);

        $route_name = $this->route_name;
        $fields = $this->es_feed->getEsFields($feed_id);
        $channel_feed = $this->channel_feed->getChannelFeed($channel_feed_id);
        $custom_field_name = $this->channel_custom_mapping->getCustomFields($channel_feed_id,$channel_type_id);

        $channel_fields_to_map = $this->channel_mapping->getChannelMappings($channel_feed->fk_channel_id,$channel_type_id);

        $pluck_channel_fields  = $this->channel_mapping->getChannelMappings($channel_feed->fk_channel_id,$channel_type_id,true);
        $get_mapped_items = $this->channel_feed_mapping->getMappedItems($channel_feed_id,$channel_type_id);
        $suggestor = [];
        if(count($get_mapped_items)  ==  0 ) {
            $suggestor = ChannelMapping::suggestChannelMapping($fields,$pluck_channel_fields);
        }

        $channel_id = $channel_feed->fk_channel_id;
        return view('dfcore.channel.finalize')->with(compact('feed_id','fields','channel_id','channel_fields_to_map',
            'channel_feed_id','channel_id','get_mapped_items','channel_type_id','wizard','route_name','channel_type_id','suggestor','custom_field_name'));
    }


    /**
     * @param $feed_id
     * @param Request $request
     */
    public function ajax_get_channel(Request $request)
    {
        $country_id = $request->get('country_id');
        return \Illuminate\Support\Facades\Response::json($this->channel->getChannelByCountry($country_id));

    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function ajax_get_channel_type(Request $request)
    {
        $channel_id = $request->get('channel_id');
        return \Illuminate\Support\Facades\Response::json($this->channel_type->getChannelTypeByChannel($channel_id));

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post_channel_map_feed(Request $request)
    {

        $number_of_items = $request->get('number_of_items');
        $feed_row_array = $request->get('feed_row');
        $channel_mapping_array = $request->get('fk_channel_mapping_id');
        $channel_mapping_name = $request->get('channel_mapping_name');
        $this->channel_feed_mapping->removeChannelFieldMapping($request->get('fk_channel_feed_id'));
        $extra_field_name = $request->get('extra_field_name');
        $extra_map_to_field = $request->get('extra_map_to_field');


        $this->channel_custom_mapping->removeCustomChannelMapping($request->get('fk_channel_feed_id'),$request->get('fk_channel_type_id'));
        $this->spreadsheet_header->removeSpreadsheetHeadersByChannel($request->get('fk_channel_feed_id'),$request->get('fk_channel_type_id'));


        /**
         * if we have custom fields add them over here
         */
        if(!is_null($extra_field_name)) {
            foreach($extra_field_name as $extra_field_key => $extra_field) {
                $custom_field_name = [
                    'fk_channel_feed_id' => $request->get('fk_channel_feed_id'),
                    'fk_channel_type_id' => $request->get('fk_channel_type_id'),
                    'fk_feed_id' => $request->get('fk_feed_id'),
                    'custom_field_name' => MappingValidator::formatMapping($extra_field,'_'),
                    'field_name'=>$extra_map_to_field[$extra_field_key]
                ];
                $this->channel_custom_mapping->createCustomChannel($custom_field_name);
            }
        }



        for($i=0; $i< $number_of_items; $i++) {

            if(isset($feed_row_array[$i]) && $feed_row_array[$i] !== null ) {
                $data = $request->only(['fk_channel_id','fk_feed_id','fk_channel_feed_id','fk_channel_type_id']);
                $data['fk_channel_mapping_id'] = $channel_mapping_array[$i];
                $data['feed_row_name'] = $feed_row_array[$i];

                if($this->channel_feed_mapping->hasDuplicateFieldName($request->get('fk_channel_feed_id'),$request->get('fk_channel_type_id'),$feed_row_array[$i]) == false) {

                    $this->spreadsheet_header->saveSpreadsheetHeaders(
                        [
                            'spreadsheet_header'=>$channel_mapping_name[$i],
                            'fk_feed_id'=>$request->get('fk_feed_id'),
                            'fk_channel_type_id'=>$request->get('fk_channel_type_id'),
                            'fk_channel_feed_id'=>$request->get('fk_channel_feed_id'),
                        ]);

                }


                $this->channel_feed_mapping->createChannelFeedMapping($data);
            }

        }

        $this->channel_feed->createChannelFeed(['updating'=>true],$request->get('fk_channel_feed_id'));
        return redirect()->route('spreadsheet.browse_feed',
            [
                'feed_id'=>$request->get('fk_feed_id'),
                'channel_feed_id'=>$request->get('fk_channel_feed_id'),
                'channel_type_id'=>$request->get('fk_channel_type_id'),
                'url_key'=>UrlKey::CHANNEL_FEED,
                'reimport'=>true
            ]);
    }


    /**
     * @param Request $request
     */
    public function post_channel_setting(Request $request)
    {
        $channel_feed_id = (int) $request->get('channel_feed_id');
        $data = $request->only(['fk_channel_id','fk_channel_type_id','active','fk_country_id','update_interval']);
        $data['fk_feed_id'] = $request->get('feed_id');
        $data['name'] = $request->get('name');
        if($channel_feed_id == 0 ) {
            $data['next_update'] = \Carbon\Carbon::now()->tz(DFBULDER_TIMEZONE)->addSecond($data['update_interval']);
            $channel_feed_id =  $this->channel_feed->createChannelFeed($data);
        } else {
            $data['next_update'] = \Carbon\Carbon::now()->tz(DFBULDER_TIMEZONE)->addSecond($data['update_interval']);
            $this->channel_feed->createChannelFeed($data,$channel_feed_id);
        }


        return redirect()->route('filter.categorize_feed',
            ['feed_id'=>$data['fk_feed_id'],
            'channel_feed_id'=>$channel_feed_id,
            'channel_type_id'=>$request->get('fk_channel_type_id'),
            'url_key'=>UrlKey::CHANNEL_FEED
            ]);
    }


    /**
     * @param $channel_feed_id
     * @param $feed_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove_channel_feed($channel_feed_id,$feed_id, Request $request)
    {

        $index_name = createEsIndexName($channel_feed_id, ESIndexTypes::CHANNEL);
        $channel = new ESChannel( $index_name,DFBUILDER_ES_TYPE);

        try {
            $channel->deleteIndex();
        } catch (\Exception $e) {
            FeedlogFacade::addAlert($feed_id,$e->getMessage());
        }

        $this->channel_feed->removeChannelFeed($channel_feed_id);
        $request->session()->flash('flash_success_message',trans('messages.channel_create_lbl17'));
        return redirect()->route('channel.channel_settings',['feed_id'=>$feed_id]);

    }


}