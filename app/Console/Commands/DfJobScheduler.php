<?php

namespace App\Console\Commands;

use App\Entity\Adwordsfeed;
use App\Entity\ChannelFeed;
use App\Entity\Feed;
use App\Entity\Repository\AdwordsfeedRepository;
use App\Entity\Repository\ChannelFeedRepository;
use App\Entity\Repository\FeedRepository;
use App\Jobs\Importfeed;
use App\Jobs\UpdateAdwords;
use App\Jobs\UpdateChannel;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DfJobScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dfjobscheduler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will check everyminute of new jobs needs to be added to the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**
         * Update the channels over here...
         */
        $channel_feed_repository = new ChannelFeedRepository(new ChannelFeed());
        $channel_feeds = $channel_feed_repository->getActiveChannels(true);
        foreach($channel_feeds as $channel_feed) {
            if(time() > strtotime($channel_feed->next_update)) {
                dispatch(new UpdateChannel($channel_feed->id,$channel_feed->fk_feed_id,$channel_feed->fk_channel_type_id,true));
                $this->comment("updating  channel". $channel_feed->id);
                $channel_feed_repository->createChannelFeed(['updating'=>false,'next_update'=>Carbon::now()->tz(DFBULDER_TIMEZONE)->addSeconds($channel_feed->update_interval)],$channel_feed->id);
            }
        }



        /**
         * Update Google Adwords over here
         */
        $adwords_feed_repository = new AdwordsfeedRepository(new Adwordsfeed());
        foreach($adwords_feed_repository->getAllAdwordsFeeds(true) as $adwords_feed) {
            if(time() > strtotime($adwords_feed->next_update)) {
                dispatch(new UpdateAdwords($adwords_feed->id,$adwords_feed->fk_feed_id,true,false));
                $this->comment("updating adwords  ". $adwords_feed->id);
                $adwords_feed_repository->createAdwordsFeed(['updating'=>false,'next_update'=>Carbon::now()->tz(DFBULDER_TIMEZONE)->addSeconds($adwords_feed->update_interval)],$adwords_feed->id);
            }
        }



        /**
         * Update the basic feeds over here...
         */
        $feed_repository = new FeedRepository( new Feed());
        foreach($feed_repository->getAllActiveFeeds(true) as $active_feeds) {
            if(time() > strtotime($active_feeds->next_update)) {
                dispatch(new Importfeed($active_feeds->id,false));
                $this->comment("updating feed ". $active_feeds->id);
                $feed_repository->createFeed(['updating'=>false,'next_update'=>Carbon::now()->tz(DFBULDER_TIMEZONE)->addSeconds($active_feeds->update_interval)],$active_feeds->id);
            }
        }



    }
}
