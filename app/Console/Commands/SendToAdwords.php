<?php

namespace App\Console\Commands;

use App\DfCore\DfBs\Adwords\AdwordsApiPusher;
use App\DfCore\DfBs\Adwords\AdwordsApiWrapper;
use App\DfCore\DfBs\Enum\AdwordsOptions;
use App\Entity\AdCampaignPreview;
use App\Entity\AdgroupPreview;
use App\Entity\AdwordsConfiguration;
use App\Entity\Repository\AdCampaignPreviewRepository;
use App\Entity\Repository\AdgroupPreviewRepository;
use App\Entity\Repository\AdwordsConfigurationRepository;
use Illuminate\Console\Command;

class SendToAdwords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_to_adwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //
        $fk_adwords_feed_id = 7;
        $adcampaign_preview_repository = new AdCampaignPreviewRepository(new AdCampaignPreview());
        $adwords_configuration_repository = new AdwordsConfigurationRepository(new AdwordsConfiguration());
        $adgroup_preview_repository = new AdgroupPreviewRepository(new AdgroupPreview());


        $get_adwords_configuration = $adwords_configuration_repository->getAdwordsConfiguration($fk_adwords_feed_id);


        $AdwordsApiPusher = new AdwordsApiPusher();
        /**
         * Get all the campaigns
         */
        foreach($adcampaign_preview_repository->getPreviewCampaigns($fk_adwords_feed_id,false) as $campaigns) {

           if($campaigns->adwords_id == 0 ) {


               /**
                * Stuur campagne naar adwords
                */
               $adwords_campaign_id = $AdwordsApiPusher->createCampaign($campaigns->campaign_name,$get_adwords_configuration->daily_budget,AdwordsOptions::DISPLAY_NETWORK,AdwordsOptions::CAMPAIGN_PAUSED);
               $adcampaign_preview_repository->createCampaignPreview(['adwords_id'=>$adwords_campaign_id],$campaigns->id);

               /**
                * Stuur Adgroup naar adwords
                */
               foreach($adgroup_preview_repository->getAdgroupFromCampaign($campaigns->id) as $adgroups) {
                   $adgroup_id = $AdwordsApiPusher->createAdGroup($adwords_campaign_id,$adgroups->adgroup_name,$get_adwords_configuration->cpc);
                   $adgroup_preview_repository->createAdgroupPreview(['adwords_id'=>$adgroup_id],$adgroups->id);
               }



           }

        }




    }
}
