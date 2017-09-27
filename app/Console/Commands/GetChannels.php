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

namespace App\Console\Commands;

use App\DfCore\DfBs\Import\Mapping\MappingValidator;
use App\Entity\Channel;
use App\Entity\ChannelMapping;
use App\Entity\ChannelType;
use App\Entity\ChannelCountry;
use App\Entity\Repository\ChannelMappingRepository;
use App\Entity\Repository\ChannelRepository;
use App\Entity\Repository\ChannelTypeRepository;
use App\Entity\Repository\ChannelCountryRepository;
use Illuminate\Console\Command;

class GetChannels extends Command
{



    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consume:channels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the channels from an API';


    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $channel_api;

    public function __construct()
    {

        parent::__construct();
        $this->channel_api = new \App\DfCore\DfBs\Channels\ChannelConsumer();
    }



    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->error("Please note ==> The fieldnames with dots in it will be replaced by a -");

       $channel_data = $this->channel_api->fetchChannelData();
       $country_rep = new ChannelCountryRepository(new ChannelCountry());
       $channel_repo = new ChannelRepository(new Channel());
       $channeltype_repo = new ChannelTypeRepository(new ChannelType());
       $channel_mapping_rep = new ChannelMappingRepository(new ChannelMapping());
        /**
         * Fetch country data
         */
       foreach($this->channel_api->fetchCountryData() as $country_data){
           $country_rep->createCountry($country_data);
       }

        /**
         * Insert main channel data
         */
       foreach($channel_data as  $channel_id => $channel) {
           $channel_repo->createChannel($channel_data[$channel_id]['channels'][0]);
       }

        /**
         * Update the channel types...
         */
        foreach($channel_data as  $channel_id => $channel_type) {
           foreach($channel_type['channel_types'] as $types) {
               $channeltype_repo->createChannelType($types);
            }
        }

        /**
         * Update the channel mappings
         */

        foreach($channel_data as  $channel_id => $channel_type) {

            foreach($channel_type['channel_mapping'] as $mappings) {
              $mappings['channel_field_name']  = str_replace(".","-",$mappings['channel_field_name']);
              $channel_mapping_rep->createChannelMapping($mappings);
            }
        }

    }


}
