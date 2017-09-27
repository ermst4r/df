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

use App\DfCore\DfBs\Enum\ConditionSelector;
use App\DfCore\DfBs\Enum\ESIndexTypes;
use App\DfCore\DfBs\Enum\ImportType;
use App\DfCore\DfBs\Enum\RevisionType;
use App\DfCore\DfBs\Enum\UrlKey;
use App\DfCore\DfBs\FileWriter\FeedWriter;
use App\DfCore\DfBs\Rules\Wizard\ChannelWizard;
use App\ElasticSearch\ESHot;
use App\Entity\Repository\Contract\iChannel;
use App\Entity\Repository\Contract\iChannelFeed;
use App\Entity\Repository\Contract\iChannelFeedMapping;
use App\Entity\Repository\Contract\iFeed;
use App\Entity\Repository\Contract\iRevision;
use App\Entity\Repository\Contract\iSpreadsheetHeader;
use App\Jobs\UpdateChannel;
use App\Jobs\UpdateRules;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Route;

class SpreadSheetController extends Controller
{

    /**
     * @var
     */
    private $channel;
    /**
     * @var iFeed
     */
    private $feed;
    /**
     * @var
     */
    private $spreadsheet_header;

    /**
     * @var
     */
    private $get_feed;

    /**
     * @var
     */
    private $es_manager;

    /**
     * @var
     */
    private $revision;

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
    private $index_name;

    /**
     * @var
     */
    private $channel_feed;

    /**
     * @var
     */
    private $channel_feed_mapping;


    /**
     * SpreadSheetController constructor.
     * @param iFeed $feed
     * @param Request $request
     * @param iSpreadsheetHeader $spreadsheet_header
     * @param iRevision $revision
     * @param iChannelFeed $channel_feed
     * @param iChannelFeedMapping $channel_feed_mapping
     * @param iChannel $channel
     * @throws \Exception
     */
    public  function __construct(iFeed $feed ,
                                 Request $request,
                                 iSpreadsheetHeader $spreadsheet_header,
                                 iRevision $revision,
                                 iChannelFeed $channel_feed,
                                 iChannelFeedMapping $channel_feed_mapping,
                                 iChannel $channel
                                 )
    {
        $this->feed     = $feed;
        $this->spreadsheet_header   = $spreadsheet_header;
        $this->revision     = $revision;
        $this->channel_feed_mapping     = $channel_feed_mapping;
        $this->channel  = $channel;

        if(php_sapi_name() != 'cli') {
            $feed_id = (  is_null($request->route()->parameter('id')) ? $request->get('id') : $request->route()->parameter('id'));
            $this->url_key =  (int) $request->get('url_key');
            $this->get_feed =  $this->feed->getFeed( $feed_id );
            $channel_feed_id =  $request->get('channel_feed_id');

            /**
             * This is a channel feed, so load the correct index.
             */

            switch($this->url_key) {
                case UrlKey::CHANNEL_FEED:
                    $index_name = createEsIndexName($channel_feed_id,ESIndexTypes::CHANNEL);
                break;
                default:
                    $index_name = createEsIndexName($channel_feed_id,ESIndexTypes::CHANNEL);
            }

            $this->channel_feed = $channel_feed;
            $this->index_name = $index_name;
            $this->es_manager = new ESHot($this->index_name,DFBUILDER_ES_TYPE);
            $this->route_name = Route::currentRouteName();


        }

        if(is_null($this->get_feed) && php_sapi_name() != 'cli' ) {
            throw new \Exception("Invalid feed id for this controller");
        }
    }


    /**
     * @param $id
     * @return view
     */
    public function browse_feed($id, Request $request)
    {

        $channel_feed_id = $request->get('channel_feed_id');
        $channel_type_id = $request->get('channel_type_id');
        $reimport = (int) $request->get('reimport');
        $force_reimport = (int) $request->get('force_reimport');

        $channel_feed = $this->channel_feed->getChannelFeed($channel_feed_id);
        $get_channel = $this->channel->getChannel($channel_feed->fk_channel_id);
        $FeedWriter = new FeedWriter();
        $generated_file_name = $FeedWriter->generateFileName($channel_feed_id,$get_channel->channel_export);
        $fields = $this->channel_feed_mapping->getMappingTemplate($channel_feed_id,$channel_type_id,false);

        $wizard = ChannelWizard::getNavigation($this->url_key,['feed_id'=>$id,'channel_feed_id'=>$channel_feed_id,'channel_type_id'=>$channel_type_id]);
        $route_name = $this->route_name;

        /**
         * Do a import
         */
        if($reimport == 1) {
            dispatch((new UpdateChannel($channel_feed_id,$id, $channel_type_id))->onQueue('high'));
        }


        $channel_wizard = ChannelWizard::getNavigation($this->url_key,
            ['feed_id'=>$id,
            'channel_feed_id'=>$channel_feed_id,
            'channel_type_id'=>$request->get('channel_type_id'),
            ]);

        $url_key = $this->url_key;
        $route_name = $this->route_name;
        $index_exists = $this->es_manager->client->indices()->exists(['index'=>$this->index_name]);
        $item_number = 0;
        $spreadsheet_headers = $this->spreadsheet_header->pluckSpreadSheetHeaders($channel_feed_id,$channel_type_id);
        return view('dfcore.spreadsheet.browse_feed')->with(compact('id','item_number','fields',
            'spreadsheet_headers','url_key','route_name','channel_feed',
            'channel_wizard','channel_feed_id','wizard','index_exists','channel_type_id','force_reimport','generated_file_name',
            'url_key','get_channel',
            'route_name'));

    }


    /**
     * @param $id
     * @param Request $request
     */
    public function ajax_revision($id, Request $request)
    {
        $type = $request->get('type');
        $data_object = json_decode($request->get('data_object'),true);
        $affected_rows = [];

        switch($type) {
            case RevisionType::UPDATE;
                foreach($data_object as $data) {

                    $updated = $this->es_manager->updateDocument(
                        $data['generated_id'],
                        [
                            $data['revision_field_name']=>$data['revision_new_content']
                        ]
                    );

                    if($updated !== false ){
                        $affected_rows[] = $this->revision->setUpdateRevision($data);
                    }

                }
            break;
            case RevisionType::DELETE:
                 $this->revision->setDeleteRevision($data_object,$id,$request->get('channel_feed_id'),$request->get('channel_type_id'));
                 $this->es_manager->removeBulkData($data_object);
            break;

        }
        return \Illuminate\Support\Facades\Response::json($affected_rows);

    }

    /**
     * Save the feed headers...
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save_headers($id,Request $request)
    {
        $channel_feed_id = $request->get('channel_feed_id');
        $channel_type_id = $request->get('channel_type_id');
        $show_fields = $request->get('show_fields');
        $url_key = $request->get('url_key');
        $this->spreadsheet_header->removeSpreadsheetHeadersByChannel($channel_feed_id,$channel_type_id);
        foreach($show_fields as $fields) {

            $this->spreadsheet_header->saveSpreadsheetHeaders(['fk_feed_id'=>$id,'spreadsheet_header'=>$fields,'fk_channel_type_id'=>$channel_type_id,'fk_channel_feed_id'=>$channel_feed_id]);
        }
        $request->session()->flash('flash_success_message','Headers met succes opgeslagen!');
        return redirect()->route('spreadsheet.browse_feed',['id'=>$id,'url_key'=>$url_key,'channel_type_id'=>$channel_type_id,'channel_feed_id'=>$channel_feed_id]);

    }

    /**
     * @return mixed
     */
    public function ajax_browse_hot($id,Request $request)
    {
        $items_per_page = DFBUILDER_DEFAULT_ES_LIMIT;
        $page = $request->get('page');
        $field = $request->get('field');
        $channel_feed_id = $request->get('channel_feed_id');
        $channel_type_id = $request->get('channel_type_id');
        $term = $request->get('term');
        $selected_condition = (is_null($request->get('selected_condition')) || $request->get('selected_condition') == 0  ? ConditionSelector::BY_FEED :  (int) $request->get('selected_condition') );
        $offset = ($page - 1)  * $items_per_page;
        $spreadsheet_headers = $this->spreadsheet_header->pluckSpreadSheetHeaders($channel_feed_id,$channel_type_id);

        return \Illuminate\Support\Facades\Response::json($this->es_manager->readableHotData($id,$offset,$items_per_page,$selected_condition,$term,$field,$spreadsheet_headers));

    }


    public function run_job(Request $request)
    {
        dispatch((new UpdateChannel($request->get('channel_feed_id'),$request->get('feed_id'), $request->get('channel_type_id')))->onQueue('high'));
    }


}