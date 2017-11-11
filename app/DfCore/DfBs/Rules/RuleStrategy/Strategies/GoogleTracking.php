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


/*
  |--------------------------------------------------------------------------
  | Search a value in another field and explode / implode values
  |--------------------------------------------------------------------------
  |
  */
namespace App\DfCore\DfBs\Rules\RuleStrategy\Strategies;


use App\DfCore\DfBs\Enum\RuleConditions;

class GoogleTracking
    extends AbstractBaseRule
    implements iContract
{
    /**
     * Each rule has is own manner of handling.
     * Per strategy we handle the rules depending on the form input what has been given in the front-end.
     * @param $es_record
     * @param $then_field
     * @param $then_field_values
     * @param $then_spacing
     * @return array
     */
    public function handle($es_record, $then_field, $then_field_values,$then_spacing)
    {

        $source = (isset($then_field_values[0]) ? $then_field_values[0] : '');
        $medium = (isset($then_field_values[1]) ? $then_field_values[1] : '');
        $campaign = (isset($then_field_values[2]) ? $then_field_values[2] : '');
        $term = (isset($then_field_values[3]) ? $then_field_values[3] : '');
        $content = (isset($then_field_values[4]) ? $then_field_values[4] : '');

        if(isset($es_record['_source'][$then_field])) {

            $field_values = $this->formatFeedFields($es_record);
            $source = strtr($source, $field_values);
            $medium = strtr($medium, $field_values);
            $campaign = strtr($campaign, $field_values);
            $term = strtr($term, $field_values);
            $content = strtr($content, $field_values);
            $url_parts = parse_url($es_record['_source'][$then_field]);

            /**
             * concat the url parts
             */
            if(isset($url_parts['query'])) {
                parse_str($url_parts['query'], $params);
                $params['utm_source']   =     $source;
                $params['utm_campaign'] =     $campaign;
                $params['utm_medium']   =     $medium;
                $params['utm_term']     =        $term;
                $params['utm_content']  =        $content;
                $url_parts['query'] = http_build_query($params);
                $es_record['_source'][$then_field] = $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'] . '?' . $url_parts['query'];
            }


        }

        return $es_record;

    }

    public function getRuleType()
    {
        return RuleConditions::THEN_GOOGLE_TRACKING;
    }



}