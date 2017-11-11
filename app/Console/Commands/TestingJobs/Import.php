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


use App\DfCore\DfBs\Enum\ImportStatus;
use App\DfCore\DfBs\Enum\ImportType;
use App\DfCore\DfBs\Enum\LogStates;
use App\DfCore\DfBs\Import\Facade\ImportFeedFacade;
use App\DfCore\DfBs\Log\FeedlogFacade;
use App\DfCore\DfBs\Rules\RuleCronjobFacade;
use App\ElasticSearch\DynamicFeedRepository;
use App\Entity\CustomMapping;
use App\Entity\Feed;
use App\Entity\Repository\CustomMappingRepository;
use App\Entity\Repository\FeedRepository;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


use Illuminate\Console\Command;
class Import extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update products';



    protected $feed_id;

    /**
     * @var mixed
     */
    protected $feed; /**
 *
 * @var mixed
 */
    protected $get_feed;

    /**
     * @var ImportFeedFacade
     */
    private $importfeedFacade;

    /**
     * @var
     */
    private $first_import;


    /**
     * Importfeed constructor.
     * @param $feed_id
     * @param bool $first_import
     */
    public function __construct()
    {

        /**
         * WARNING <=====
         * please leave this commented otherwise the job wont work
         */
        parent::__construct();
//        $this->feed = new FeedRepository(new Feed());
//        $this->feed_id = 6;
//        $this->get_feed = $this->feed->getFeed($this->feed_id);
//        $this->importfeedFacade = new ImportFeedFacade();
//        $this->first_import = true;

    }







    public function failed(\Exception $e)
    {
        $this->feed->createFeed(['feed_status'=>ImportStatus::FAILED],$this->feed_id);
        event(new \App\Events\FeedImported($this->feed_id,false));
        FeedlogFacade::addAlert($this->feed_id,$e->getMessage(),LogStates::CRITICAL);
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        event(new \App\Events\FeedImported($this->feed_id,true));
        $index_name =  createEsIndexName($this->feed_id);
        $DynamicFeedRepository = new DynamicFeedRepository($index_name,DFBUILDER_ES_TYPE);
        $customMappingRepository = new CustomMappingRepository(new CustomMapping());
        $get_custom_mapping = $customMappingRepository->getCustomMapping($this->feed_id,true,'fk_feed_id');
        $feed_args = $this->importfeedFacade->initializeFeed($this->feed_id,$this->feed, $this->get_feed);
        $this->importfeedFacade->refreshIndex($index_name,$DynamicFeedRepository);
        $this->importfeedFacade->doImport($this->feed_id,$DynamicFeedRepository,$this->feed,$feed_args,true,$get_custom_mapping);



    }


}
