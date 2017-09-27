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

use App\DfCore\DfBs\Enum\ImportStatus;
use App\DfCore\DfBs\Enum\ImportType;
use App\DfCore\DfBs\Enum\LogStates;
use App\DfCore\DfBs\Enum\TasklogEnum;
use App\DfCore\DfBs\Import\Facade\ImportFeedFacade;
use App\DfCore\DfBs\Log\FeedlogFacade;
use App\DfCore\DfBs\Log\LoggerFacade;
use App\DfCore\DfBs\Rules\RuleCronjobFacade;
use App\ElasticSearch\DynamicFeedRepository;
use App\Entity\CustomMapping;
use App\Entity\Feed;
use App\Entity\Repository\CustomMappingRepository;
use App\Entity\Repository\FeedRepository;
use App\Entity\Repository\TasklogRepository;
use App\Entity\Tasklog;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class Importfeed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;



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
     * @var TasklogRepository
     */
    private $task_log;

    private $task;

    /**
     * Importfeed constructor.
     * @param $feed_id
     * @param bool $first_import
     */
    public function __construct($feed_id,$first_import=false)
    {

        $this->feed = new FeedRepository(new Feed());
        $this->task_log = new TasklogRepository(new Tasklog());
        $this->feed_id = (int) $feed_id;
        $this->get_feed = $this->feed->getFeed($feed_id);
        $this->importfeedFacade = new ImportFeedFacade();
        $this->first_import = $first_import;
        $this->task = $this->task_log->createTask(['fk_feed_id'=>$this->feed_id,'task'=>'Importing feed','status'=>TasklogEnum::BUSY]);
    }







    public function failed(\Exception $e)
    {
        $this->feed->createFeed(['feed_status'=>ImportStatus::FAILED],$this->feed_id);
        event(new \App\Events\FeedImported($this->feed_id,false));
        FeedlogFacade::addAlert($this->feed_id,$e->getMessage(),LogStates::CRITICAL);
        $this->task_log->createTask(['status'=>TasklogEnum::FAILED,'task'=>'Productfeed import failed!','fk_feed_id'=>$this->feed_id],$this->task->id);
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {




        $index_name =  createEsIndexName($this->feed_id);
        $DynamicFeedRepository = new DynamicFeedRepository($index_name,DFBUILDER_ES_TYPE);
        $feed_args = $this->importfeedFacade->initializeFeed($this->feed_id,$this->feed, $this->get_feed);
        $customMappingRepository = new CustomMappingRepository(new CustomMapping());
        $get_custom_mapping = $customMappingRepository->getCustomMapping($this->feed_id,true,'fk_feed_id');
        $this->importfeedFacade->refreshIndex($index_name,$DynamicFeedRepository);
        $this->importfeedFacade->doImport($this->feed_id,$DynamicFeedRepository,$this->feed,$feed_args,true,$get_custom_mapping);
        if(!$this->first_import) {
            RuleCronjobFacade::updateAllRulesFacade($this->feed_id);
            RuleCronjobFacade::updateAllFiltersFacade($this->feed_id);
        }

        $this->task_log->createTask(['status'=>TasklogEnum::FINISHED,'task'=>'Productfeed imported!','fk_feed_id'=>$this->feed_id],$this->task->id);
        event(new \App\Events\FeedImported($this->feed_id,true));




    }





}
