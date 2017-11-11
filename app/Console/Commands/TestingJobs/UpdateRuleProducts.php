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

namespace App\Console\Commands\TestingJobs;

use App\DfCore\DfBs\Channels\ExportChannels\ChannelStrategy;
use App\DfCore\DfBs\Enum\ESImportType;
use App\DfCore\DfBs\Enum\ESIndexTypes;
use App\DfCore\DfBs\Enum\ImportType;
use App\DfCore\DfBs\FileWriter\FeedWriter;
use App\DfCore\DfBs\Import\Facade\ImportFeedFacade;
use App\DfCore\DfBs\Import\Mapping\Mapping;
use App\DfCore\DfBs\Log\FeedlogFacade;
use App\DfCore\DfBs\Rules\Builder\CategoryDirector;
use App\DfCore\DfBs\Rules\Builder\FeedOperationDirector;
use App\DfCore\DfBs\Rules\Builder\RevisionDirector;
use App\ElasticSearch\DynamicFeedRepository;
use App\ElasticSearch\ESChannel;
use App\Entity\Channel;
use App\Entity\ChannelCustomMapping;
use App\Entity\ChannelFeed;
use App\Entity\ChannelFeedMapping;
use App\Entity\CustomMapping;
use App\Entity\Feed;
use App\Entity\Repository\ChannelCustomMappingRepository;
use App\Entity\Repository\ChannelFeedMappingRepository;
use App\Entity\Repository\ChannelFeedRepository;
use App\Entity\Repository\ChannelRepository;
use App\Entity\Repository\CustomMappingRepository;
use App\Entity\Repository\FeedRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;;
class UpdateRuleProducts extends Command
{



    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_rule_products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update products';


    /**
     * @var int
     */
    private $feed_id;
    /**
     * @var int
     */
    private $channel_feed_id;
    /**
     * @var int
     */
    private $channel_type_id;
    /**
     * @var ImportFeedFacade
     */
    private $import_feed_facade;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->feed_id = 2;
        $this->channel_feed_id = 2;
        $this->channel_type_id = 103;
        $this->import_feed_facade = new ImportFeedFacade();

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {





        $feed_id = $this->feed_id;
        $channel_feed_id = $this->channel_feed_id;

        /**
         * Prefill all the products...
         */
        $tmp_index_name =  createEsIndexName($feed_id,ESIndexTypes::TMP);
        $tmp_index = new DynamicFeedRepository($tmp_index_name, DFBUILDER_ES_TYPE);
        $all_products = $tmp_index->getAllDocuments(true);


        /**
         * Reimport the feed
         */
        $feed = new FeedRepository(new Feed());
        $customMappingRepository = new CustomMappingRepository(new CustomMapping());
        $get_custom_mapping = $customMappingRepository->getCustomMapping($this->feed_id,true,'fk_feed_id');
        $get_feed = $feed->getFeed($feed_id);
        $index_name =  createEsIndexName($this->feed_id);
        $DynamicFeedRepository = new DynamicFeedRepository($index_name,DFBUILDER_ES_TYPE);
        $feed_args = $this->import_feed_facade->initializeFeed($this->feed_id,$feed, $get_feed);
        $this->import_feed_facade->refreshIndex($index_name,$DynamicFeedRepository);
        $this->import_feed_facade->doImport($this->feed_id,$DynamicFeedRepository,$feed,$feed_args,true,$get_custom_mapping);


        $ChannelRepository = new ChannelRepository(new Channel());

        $channel_index_name = createEsIndexName($channel_feed_id,'channel');
        $ChannelFeedMapping = new ChannelFeedMappingRepository(new ChannelFeedMapping());
        $ChannelCustomMapping = new ChannelCustomMappingRepository(new ChannelCustomMapping());
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


        /**
         * Build the categories and the rules
         */

        $categoryDirector = new CategoryDirector();
        $category_products = $categoryDirector->buildCategoryRule($this->feed_id,$this->channel_feed_id,$all_products,DFBUILDER_ES_TYPE);

        // Rules
        $ruleDirector = new FeedOperationDirector();
        $rules = $ruleDirector->buildChannelRules($feed_id,$this->channel_feed_id,$category_products,DFBUILDER_ES_TYPE);


        // Revisions..
        $revisionDirector = new RevisionDirector();
        $content = $new_mapping->dispatchNewHeaders($rules,$channel_headers);
        $content = $revisionDirector->buildRevision($this->channel_feed_id,$content);

        // restore the array if the user has multiple selections



        /**
         * Insert into database
         * With bulk
         */
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
        $load_channel = ChannelStrategy::loadChannels($content,$mapping_template,$custom_fields,$this->channel_feed_id);


        if(isset($load_channel[$get_channel_feed->fk_channel_id])) {
            $get_channel = $ChannelRepository->getChannel($get_channel_feed->fk_channel_id);
            $feed_data = $load_channel[$get_channel_feed->fk_channel_id]->buildChannel();
            $Feedwriter->writeFile($feed_data,$this->channel_feed_id,$get_channel->channel_export);

        } else {
            FeedlogFacade::addAlert($feed_id,"A channel has not been registerd! Expecting channel feed id ".$get_channel_feed->fk_channel_id);
        }
        $ChannelFeedRepository->createChannelFeed(['updating'=>false],$channel_feed_id);
        event(new \App\Events\RulesUpdated($channel_feed_id,$this->channel_type_id));

    }


}
