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

namespace App\Jobs;


use App\DfCore\DfBs\Channels\ExportChannels\ChannelStrategy;
use App\DfCore\DfBs\Enum\ESIndexTypes;
use App\DfCore\DfBs\Enum\LogStates;
use App\DfCore\DfBs\Enum\TasklogEnum;
use App\DfCore\DfBs\FileWriter\FeedWriter;
use App\DfCore\DfBs\Import\Facade\ImportFeedFacade;
use App\DfCore\DfBs\Import\Mapping\Mapping;
use App\DfCore\DfBs\Log\FeedlogFacade;
use App\DfCore\DfBs\Log\LoggerFacade;
use App\DfCore\DfBs\Rules\RuleCronjobFacade;
use App\Entity\Channel;
use App\Entity\ChannelCustomMapping;
use App\Entity\ChannelFeed;
use App\Entity\CustomMapping;
use App\Entity\Feed;
use App\Entity\Repository\ChannelCustomMappingRepository;
use App\Entity\Repository\ChannelFeedRepository;
use App\Entity\Repository\ChannelRepository;
use App\Entity\Repository\CustomMappingRepository;
use App\Entity\Repository\FeedRepository;
use App\Entity\Repository\TasklogRepository;
use App\Entity\Tasklog;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\DfCore\DfBs\Enum\ESImportType;
use App\DfCore\DfBs\Rules\Builder\CategoryDirector;
use App\DfCore\DfBs\Rules\Builder\FeedOperationDirector;
use App\DfCore\DfBs\Rules\Builder\RevisionDirector;
use App\ElasticSearch\DynamicFeedRepository;
use App\Entity\ChannelFeedMapping;
use App\Entity\Repository\ChannelFeedMappingRepository;
use Carbon\Carbon;
class UpdateChannel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $channel_feed_id;
    protected $channel_type_id;
    protected $feed_id;
    protected $feed;
    protected $es_rules;
    protected $update_all;
    private $import_feed_facade;
    private $task_log;
    private $task;
    private $channel_feed;
    private $get_channel_feed;

    /**
     * @param \Exception
     */
    public function failed(\Exception $e)
    {
        $ChannelFeedRepository = new ChannelFeedRepository(new ChannelFeed());
        $ChannelFeedRepository->createChannelFeed(['updating'=>false],$this->channel_feed_id);
        $this->task_log->createTask(['status'=>TasklogEnum::FAILED,'task'=>'Channel import failed!','fk_feed_id'=>$this->feed_id],$this->task->id);
        FeedlogFacade::addAlert($this->feed_id,$e->getMessage(),LogStates::CRITICAL);
    }


    /**
     * UpdateRules constructor.
     * @param $channel_feed_id
     * @param $feed_id
     * @param $channel_type_id
     */
    public function __construct($channel_feed_id,$feed_id,$channel_type_id,$update_all=false)
    {
        $this->channel_feed = new ChannelFeedRepository(new ChannelFeed());
        $this->get_channel_feed = $this->channel_feed->getChannelFeed($channel_feed_id);
        $this->task_log = new TasklogRepository(new Tasklog());
        $this->channel_feed_id = (int) $channel_feed_id;
        $this->channel_type_id = (int) $channel_type_id;
        $this->feed_id = (int) $feed_id;
        $this->update_all = $update_all;
        $this->feed = new FeedRepository(new Feed());
        $this->import_feed_facade = new ImportFeedFacade();
        $this->task = $this->task_log->createTask(['fk_feed_id'=>$this->feed_id,'task'=>'Importing channel '.$this->get_channel_feed->name,'status'=>TasklogEnum::BUSY]);

    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {






        $feed_id = $this->feed_id;
        $feed = new FeedRepository(new Feed());
        $channel_feed_id = $this->channel_feed_id;
        $customMappingRepository = new CustomMappingRepository(new CustomMapping());
        $get_custom_mapping = $customMappingRepository->getCustomMapping($this->feed_id,true,'fk_feed_id');
        $get_feed = $feed->getFeed($feed_id);
        $index_name =  createEsIndexName($this->feed_id);
        $DynamicFeedRepository = new DynamicFeedRepository($index_name,DFBUILDER_ES_TYPE);
        $feed_args = $this->import_feed_facade->initializeFeed($this->feed_id,$feed, $get_feed);


        if($this->update_all) {
            $this->import_feed_facade->refreshIndex($index_name,$DynamicFeedRepository);
            $this->import_feed_facade->doImport($this->feed_id,$DynamicFeedRepository,$feed,$feed_args,true,$get_custom_mapping);
            RuleCronjobFacade::updateAllRulesFacade($this->feed_id);
            RuleCronjobFacade::updateAllFiltersFacade($this->feed_id);
        }


        /**
         * Prefill all the products...
         */
        $tmp_index_name =  createEsIndexName($feed_id,ESIndexTypes::TMP);
        $tmp_index = new DynamicFeedRepository($tmp_index_name, DFBUILDER_ES_TYPE);
        // get all the products
        $all_products = $tmp_index->getAllDocuments(true);


        $channel_index_name = createEsIndexName($channel_feed_id,'channel');
        $ChannelFeedMapping = new ChannelFeedMappingRepository(new ChannelFeedMapping());
        $ChannelCustomMapping = new ChannelCustomMappingRepository(new ChannelCustomMapping());
        $ChannelRepository = new ChannelRepository(new Channel());
        $custom_fields = $ChannelCustomMapping->getCustomFields($this->channel_feed_id,$this->channel_type_id);
        $ChannelFeedRepository = new ChannelFeedRepository(new ChannelFeed());
        $get_channel_feed = $ChannelFeedRepository->getChannelFeed($this->channel_feed_id);
        $channel_headers = $ChannelFeedMapping->getMappedItems($this->channel_feed_id,$this->channel_type_id);
        $ChannelFeedRepository->createChannelFeed(['updating'=>true],$channel_feed_id);
        $Feedwriter = new FeedWriter();
        $mappings = new DynamicFeedRepository(createEsIndexName($feed_id), DFBUILDER_ES_TYPE);
        $new_mapping = new DynamicFeedRepository(createEsIndexName($channel_feed_id,'channel'),DFBUILDER_ES_TYPE);
        /**
         * Delete the index from the channel
         */
        if($new_mapping->client->indices()->exists(['index'=>$channel_index_name])) {
            $new_mapping->deleteIndex();

        }
        $get_tmp_mapping = Mapping::addChannelMapping($channel_headers,$mappings->getFeedMapping());
        $get_tmp_mapping = Mapping::attachExtraChannelMapFields($channel_headers,$get_tmp_mapping);
        $all_products = Mapping::prefillChannelIndex($all_products,$channel_headers);
        $new_mapping->createDynamicMapping($get_tmp_mapping);

       // build the categories
        $categoryDirector = new CategoryDirector();
        $category_products = $categoryDirector->buildCategoryRule($this->feed_id,$this->channel_feed_id,$all_products,DFBUILDER_ES_TYPE);
        // Rules
        $ruleDirector = new FeedOperationDirector();
        $rules = $ruleDirector->buildChannelRules($feed_id,$this->channel_feed_id,$category_products,DFBUILDER_ES_TYPE);
        // Revisions..
        $revisionDirector = new RevisionDirector();
        $content = $new_mapping->dispatchNewHeaders($rules,$channel_headers);
        $content = $revisionDirector->buildRevision($this->channel_feed_id,$content);


        //insert with blk..
        $inserts = [];
        $remove_inserts = [];
        foreach(array_values($content) as $key=>$values) {
            $inserts[] = $values['_source'];
            $remove_inserts[] = $values['_id'];
        }

        /**
         * Bulk delete / (in ES we mean updating..., but we cannot update a doc)
         */
        $new_mapping->removeBulkData($remove_inserts);


        /**
         * Bulk insert..
         */
        if(count($inserts) >0 ) {
            $new_mapping->insertBulkData($inserts,['last_updated'=>Carbon::now()->tz(DFBULDER_TIMEZONE)],ESImportType::INDEX,false);

        }


        /**
         * Load the channel from the register
         * and write the file
         */
        $mapping_template = $ChannelFeedMapping->getMappingTemplate($this->channel_feed_id,$this->channel_type_id,false);
        $load_channel = ChannelStrategy::loadChannels($content,$mapping_template,$custom_fields,$channel_feed_id);
        if(isset($load_channel[$get_channel_feed->fk_channel_id])) {
            $get_channel = $ChannelRepository->getChannel($get_channel_feed->fk_channel_id);
            $feed_data = $load_channel[$get_channel_feed->fk_channel_id]->buildChannel();
            $Feedwriter->writeFile($feed_data,$this->channel_feed_id,$get_channel->channel_export);
        } else {
            FeedlogFacade::addAlert($feed_id,"A channel has not been registerd! Expecting channel feed id ".$get_channel_feed->fk_channel_id);
        }
        $ChannelFeedRepository->createChannelFeed(['updating'=>false],$channel_feed_id);
        $this->task_log->createTask(['status'=>TasklogEnum::FINISHED,'task'=>'Imported channel '.$this->get_channel_feed->name,'fk_feed_id'=>$this->feed_id],$this->task->id);
        event(new \App\Events\RulesUpdated($channel_feed_id,$this->channel_type_id));



    }





}