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
 * Created by PhpStorm.
 * User: erm
 * Date: 15-06-17
 * Time: 14:43
 */

namespace App\DfCore\DfBs\Import\Mapping;


class ChannelMapping
{

    /**
     * Suggest the channel mapping, based on levenshtein matching
     * @param $internal_fields_array
     * @param $channel_fields_array
     * @return array
     */
    public static function suggestChannelMapping($internal_fields_array,$channel_fields_array)
    {
        $lev_min_matching_grade = DFBUILDER_CHANNEL_FINALIZE_SENSITIVITY; // adjust the matching grade for the channel mapping
        $return_arrays = [];
        foreach($internal_fields_array as $internal_fields) {
            $found = [];
            foreach($channel_fields_array as $channel_fields) {
                $lev_result = levenshtein_difference($internal_fields,$channel_fields);
                if($lev_result >= $lev_min_matching_grade ) {
                    $found[$lev_result] = [$internal_fields => $channel_fields  ] ;
                }
            }
            if(count($found) == 0 ) {
                continue;
            }
            $found_key  = max(array_keys($found));
            $found_array  =$found[$found_key];
            $found_array_value = $found_array[key($found_array)];
            $return_arrays[key($found_array)] = $found_array_value;
            unset($channel_fields_array[array_search($found_array_value,$channel_fields_array)]);

        }

        return $return_arrays;


    }

}