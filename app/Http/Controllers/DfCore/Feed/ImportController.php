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

namespace App\Http\Controllers\DfCore\Feed;
use App\DfCore\DfBs\Enum\ConditionSelector;
use App\DfCore\DfBs\Enum\ESIndexTypes;
use App\DfCore\DfBs\Enum\ImportStatus;
use App\DfCore\DfBs\Enum\ImportType;
use App\DfCore\DfBs\FileWriter\FeedWriter;
use App\DfCore\DfBs\Import\Facade\RemoveFeedFacade;
use App\DfCore\DfBs\Import\Mapping\Mapping;
use App\DfCore\DfBs\Import\Mapping\MappingFactory;
use App\DfCore\DfBs\Import\Mapping\MappingValidator;
use App\DfCore\DfBs\Import\Remote\RemoteFileService;
use App\DfCore\DfBs\Import\Xml\CustomXmlParser\Parsefeed;
use App\DfCore\DfBs\Import\Xml\XmlReaderFacade;
use App\ElasticSearch\ESChannel;
use App\ElasticSearch\ESHot;
use App\Entity\Feed;
use App\Entity\Repository\Contract\iChannelFeed;
use App\Entity\Repository\Contract\iCompositeMapping;
use App\Entity\Repository\Contract\iCsvMapping;
use App\Entity\Repository\Contract\iCustomMapping;
use App\Entity\Repository\Contract\iFeed;
use App\Entity\Repository\Contract\iFieldToMap;
use App\Entity\Repository\Contract\iXmlMapping;
use App\Http\Controllers\Controller;
use App\Jobs\Importfeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
/**
 * Class StoreController
 * @package App\Http\Controllers\DfCore
 */

class ImportController extends Controller
{


    /**
     * @var iFeed
     */
    private $feed;
    /** @var iFieldToMap  */
    private $field_to_map;
    /**
     * @var iCsvMapping
     */
    private $csv_mapping;
    /**
     * @var iXmlMapping
     */
    private $xml_mapping;
    /**
     * @var
     */
    private $composite_mapping;

    /**
     * @var
     */
    private $channel_feed;


    private $custom_mapping;
  


    /**
     * ImportController constructor.
     * @param iFeed $feed
     * @param iFieldToMap $field_to_map
     * @param iCsvMapping $csv_mapping
     * @param iXmlMapping $xml_mapping
     */
    public function __construct(iFeed $feed, iFieldToMap $field_to_map, iCsvMapping $csv_mapping, iXmlMapping $xml_mapping,iCompositeMapping $composite_mapping, iChannelFeed $channel_feed, iCustomMapping $customMapping
        )
    {
        $this->feed = $feed;
        $this->field_to_map = $field_to_map;
        $this->csv_mapping = $csv_mapping;
        $this->xml_mapping = $xml_mapping;
        $this->composite_mapping = $composite_mapping;
        $this->channel_feed = $channel_feed;
        $this->custom_mapping = $customMapping;


    }


    /**
     * @param $id
     * @return view
     */
    public function composite_key($id)
    {
        $get_feed = $this->feed->getFeed($id);
        $file_name = RemoteFileService::generateSavePath($get_feed->feed_type,$id);
        $current_composite_mappings  = $this->composite_mapping->getCompositeMapping($id);
        $XmlReader = new XmlReaderFacade($file_name,$get_feed->xml_root_node);
        $mapping_info = [];
        switch($get_feed->feed_type) {
            case ImportType::XML:
                while($node = $XmlReader->streamingNode()  ) {
                    $mapping_info = array_keys($XmlReader->prepareXmlNodeForInsertIntoDatabase($node));
                    break;
                }
            break;

            case ImportType::CSV:
            case ImportType::TXT:
              $mapping_info = MappingFactory::setMapping($file_name,$get_feed);
              $mapping_info =  Mapping::formatFeedValues($mapping_info['workable_data']);
            break;
        }




        $feed = $this->feed->getFeed($id);
        return view('dfcore.import.composite_key')->with(compact('id','feed','mapping_info','current_composite_mappings'));
    }



    public function post_composite_key(Request $request)
    {
        $composite_keys = $request->get('composite_keys');
        $id = (int) $request->get('id');
        $this->composite_mapping->removeCompositeMapping($id);
        if(!is_null($composite_keys)) {
            foreach($composite_keys as $composite_field) {

                $this->composite_mapping->createCompositeMapping(
                    [
                        'field'=>$composite_field,
                        'fk_feed_id'=>$id
                    ]
                );
            }
        }


        $request->session()->flash('flash_success_message','Composite key succesvol gewijzigd!');
        return redirect()->route('import.composite_key',['id'=>$id]);
    }

    /**
     * @param $id
     */
    public function mapping_complete($id)
    {
        $feed = $this->feed->getFeed($id);


        return view('dfcore.import.mapping_complete')->with(compact('id','feed'));

    }


    /**
     * Save the xml or CSV mapping
     * @param Request $request
     */

    public function post_mapping(Request $request)
    {


        $feed_id = $request->get('fk_feed_id');
        $type = $request->get('type');
        $extra_mapping = $request->get('extra_mapping_field');
        $this->custom_mapping->removeCustomMapping($feed_id);
        foreach($extra_mapping as $extra_fields) {
            if(!is_null($extra_fields)) {
                $this->custom_mapping->createCustomMapping(['fk_feed_id'=>$feed_id,'custom_name'=>MappingValidator::formatMapping($extra_fields)]);
            }

        }

        switch($type) {
            case ImportType::CSV:
            case ImportType::TXT:
                $this->csv_mapping->removeMapping($feed_id);
                $this->csv_mapping->createCsvMapping(Mapping::prepareToSaveCsvMapping($request));

             break;

            case ImportType::XML:
                $this->xml_mapping->removeMapping($feed_id);
                $this->xml_mapping->createXmlMapping(Mapping::prepareXmlMapping($request));
            break;
        }


        $this->feed->createFeed(['feed_status'=>ImportStatus::IMPORTING],$feed_id);
        dispatch((new Importfeed($feed_id,true))->onQueue('medium'));

        $request->session()->flash('flash_success_message',trans('messages.import_mapping_lbl11', ['type'=>$type]));
        return redirect()->route('import.manage_feeds');
    }


    /**
     * Over here we need the info for the raw feeds (xml,csv or txt)
     * @param $type
     * @param $id
     * @return mixed
     */
    public function mapping($type,$id)
    {


        $mapping = null;
        $feed  = $this->feed->getFeed($id);
        $file_name = RemoteFileService::generateSavePath($type,$id);
        $file_saved = file_exists($file_name);
        $fields_to_map = $this->field_to_map->getField();
        $custom_mappings = $this->custom_mapping->getCustomMapping($id,false,'fk_feed_id');
        $is_mapped = Mapping::isMappedFactory($this->csv_mapping,$this->xml_mapping,$type,$id);
        $plain_mapped = Mapping::plainedMappedFactory($this->csv_mapping,$this->xml_mapping,$type,$id);
        $mapping_info = [];

        if($file_saved) {

            if(strlen($feed->feed_custom_parser) >0  || !is_null($feed->feed_custom_parser)) {
                $parse_feed = new Parsefeed($file_name,$feed->feed_custom_parser);
                $parse_feed->writeNewFeedData();
                $this->feed->createFeed(['xml_root_node'=>NULL,'prepend_nodes'=>NULL,'prepend_identifier'=>NULL],$feed->id);
                $feed = $this->feed->getFeed($feed->id);

            }

            $mapping_info = MappingFactory::setMapping($file_name,$feed);
            $mapping =  $mapping_info['workable_data'];
            $mapping = Mapping::prefillMapping($mapping,$fields_to_map);
            if(empty($feed->xml_root_node) || is_null($feed->xml_root_node)) {
                ($type == ImportType::XML ? $this->feed->createFeed(['xml_root_node'=>$mapping_info['root_node']],$id ):null);
            }

        }
        $has_composite_key = $this->composite_mapping->hasCompositeMapping($id);

        return view('dfcore.import.mapping')->with(compact('custom_mappings','feed','file_saved','id','type','mapping','fields_to_map','is_mapped','plain_mapped','mapping_info','has_composite_key'));
    }



    /**
     * Give us a simple interface what feed we want to use
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function select_feed()
    {
        return view('dfcore.import.selectfeed');

    }


    /**
     * Database handling
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post_feed(Request $request)
    {
        $update_root_node = (int) $request->get('update_root_node');

        if($update_root_node == 0 ) {
            $data = $request->only(['feed_name','feed_url','feed_type','xml_root_node','update_interval','active','prepend_nodes','prepend_identifier','feed_custom_parser']);
            $data['fk_store_id']  = $request->session()->get('store_id');
            $id = (int) $request->get('id');
            // redirect back when we see that the remote url doesn't exists
            if(!RemoteFileService::checkRemoteFileExist($data['feed_url'])) {

                $request->session()->flash('flash_error_message', trans('messages.import_selectfeed_lbl8'));
                return redirect()->route('import.selectfeed');
            }
            $inserted_id =  $this->feed->createFeed($data,$id);
            return redirect()->route('import.mapping',['type'=>$data['feed_type'],'id'=>$inserted_id]);

        } else {
            $data =  $request->only(['xml_root_node','feed_type','prepend_nodes','prepend_identifier']);
            $id = (int) $request->get('id');
            $this->feed->createFeed($data,$id);
            $request->session()->flash('flash_success_message', trans('messages.import_mapping_lbl17'));

            return redirect()->route('import.mapping',['type'=>$data['feed_type'],'id'=>$id]);
        }




    }


    /**
     * Let us downlaod the file in the background.
     * @param Request $request
     * @return mixed
     */
    public function ajax_download_file(Request $request)
    {
        $id = $request->input('id');
        $type = $request->input('type');
        $feed = $this->feed->getFeed($id);
        $file_name = RemoteFileService::generateSavePath($type,$id);
        RemoteFileService::downloadFileWithCurl($feed->feed_url,$file_name);




        if($feed->feed_type == ImportType::XML) {
            $feed_type = FeedWriter::detectFeedType($file_name);
            if(count($feed_type) > 0) {
                $this->feed->createFeed($feed_type,$feed->id);
            }
        }

        return Response::json(true);
    }

    /**
     * @return $this
     */
    public function manage_feeds()
    {
        $feeds = $this->feed->getFeedByStore(session('store_id'));
        return view('dfcore.import.manage_feeds')->with(compact('feeds'));
    }


    /**
     * @param $feed_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove_feed($feed_id, Request $request)
    {
        $RemoveFeed = new RemoveFeedFacade(new Feed());
        $RemoveFeed->removeCompleteFeed($feed_id);

        /**
         * Delete all indexes from this channel.
         */
        foreach($this->channel_feed->getActiveChannelsFromFeed($feed_id) as $channel_feed) {
            $index_name = createEsIndexName($channel_feed->id, ESIndexTypes::CHANNEL);
            $channel = new ESChannel( $index_name,DFBUILDER_ES_TYPE);
            $channel->deleteIndex();
        }
        $request->session()->flash('flash_success_message', trans('messages.import_mapping_lbl19'));
        return redirect()->route('import.manage_feeds');
    }


    /**
     * @param $feed_id
     * @param $job
     * @return mixed
     */
    public function ajax_update_feed($feed_id,$job)
    {
        if($job == 'true') {
            dispatch((new Importfeed($feed_id))->onQueue('medium'));
        }
        $feed = $this->feed->getFeed($feed_id);

        return Response::json($feed);
    }


    public function feed_browse(Request $request,$feed_id)
    {
        $index_name = createEsIndexName($feed_id);
        $fields = [];
        $es_manager = new ESHot(createEsIndexName($feed_id),DFBUILDER_ES_TYPE);
        $feed_exists = $es_manager->client->indices()->exists(['index'=>$index_name]);
        $show_fields = implode(",",(is_null($request->get('show_fields')) ? [] : $request->get('show_fields') ) );
        if($feed_exists) {
            $fields = $es_manager->getEsFields($feed_id);
        }

        return view('dfcore.import.browse_feed')->with(compact('feed_id','fields','show_fields','feed_exists'));
    }

    public function ajax_preview_browse(Request $request, $feed_id)
    {

        $items_per_page = DFBUILDER_DEFAULT_ES_LIMIT;
        $es_manager = new ESHot(createEsIndexName($feed_id),DFBUILDER_ES_TYPE);

        $page = $request->get('page');
        $field = $request->get('field');
        $term = $request->get('term');
        $selected_condition = (is_null($request->get('selected_condition')) || $request->get('selected_condition') == 0  ? ConditionSelector::BY_FEED :  (int) $request->get('selected_condition') );
        $offset = ($page - 1)  * $items_per_page;
        $spreadsheet_headers = [];
        if(!is_null($request->get('prefilled_fields'))) {
            $spreadsheet_headers = explode(',',$request->get('prefilled_fields'));
        }

        return \Illuminate\Support\Facades\Response::json($es_manager->readableHotData($feed_id,$offset,$items_per_page,$selected_condition,$term,$field,$spreadsheet_headers));


    }








}
