<?php
namespace App\DfCore\DfBs\Channels;
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
 * Created by PhpStorm.
 * User: erm
 * Date: 30-05-17
 * Time: 13:39
 */
class ChannelConsumer
{
    /**
     * @var string
     */

    private $api_url = DFBUILDER_CHANNEL_CONSUMER_URL;

    /**
     * Fetch the channels
     * @return mixed
     */
    protected function fetch_channels()
    {
        $url = $this->api_url;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url
        ));
        $resp = curl_exec($curl);
        curl_close($curl);
        return json_decode($resp,true);
    }


    /**
     * Fetch the country data..
     * @return array
     */
    public function fetchCountryData()
    {

        $fetch_channels =  $this->fetch_channels();
        foreach($fetch_channels['country'] as $country) {
            $country_data[]  = [
                'id'=>$country['id'],
                'country'=>$country['country_name'],
            ];

        }
        return $country_data;
    }


    /**
     * Get us the channel data...
     * @return array
     */
    public function fetchChannelData()
    {
        $fetch_channels =  $this->fetch_channels();
        $channel_data = [];
        foreach($fetch_channels['channel'] as $channel) {


            /**
             * Fetch channels
             */

            $channel_data[$channel['id']]['channels'][] = [
                'id'=>$channel['id'],
                'channel_name'=>$channel['channel_name'],
                'channel_export'=>$channel['channel_export'],
                'channel_image'=>$channel['channel_image'],
                'fk_country_id'=>$channel['country_id']

            ];



            /**
             * Save the channel types
             */


            foreach($channel['channel_types'] as $channel_type) {
                $channel_data[$channel['id']]['channel_types'][] = [
                    'id'=>$channel_type['id'],
                    'channel_type'=>$channel_type['type'],
                    'fk_channel_id'=>$channel_type['fk_channel_id']
                ];
            }



            /**
             * Save the channel mappings
             */

            
            foreach($channel['channel_mapping'] as $mapping) {
                $channel_data[$channel['id']]['channel_mapping'][] = [
                    'id'=>$mapping['id'],
                    'channel_field_name'=>$mapping['field_name'],
                    'description'=>$mapping['description'],
                    'channel_field_type'=>$mapping['field_type'],
                    'fk_channel_id'=>$channel['id'],
                    'fk_channel_type_id'=>$mapping['type_id']
                ];
            }
        }

        return $channel_data;
    }

}