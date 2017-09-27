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
  | Round a number
  |--------------------------------------------------------------------------
  */
namespace App\DfCore\DfBs\Rules\RuleStrategy\Strategies;


use App\DfCore\DfBs\Enum\CommonRulesEnum;
use App\DfCore\DfBs\Enum\RuleConditions;

class ThenRoundNumberStrategy implements iContract
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

        /**
         * TODO implement logic
         */
        $round_number_type = (isset($then_field_values[0]) ? $then_field_values[0] : []);
        if(isset($es_record['_source'][$then_field])) {


           switch($round_number_type) {
               case CommonRulesEnum::ROUND_NUMBER_95:
                   $es_record['_source'][$then_field] = round($es_record['_source'][$then_field]) - 0.05;
               break;
               case CommonRulesEnum::ROUND_NUMBER_99:
                   $es_record['_source'][$then_field] = round($es_record['_source'][$then_field] +0.01) - 0.01;
               break;
               case CommonRulesEnum::ROUND_NUMBER_ABOVE:
                   $es_record['_source'][$then_field] = ceil($es_record['_source'][$then_field]);

               break;
               case CommonRulesEnum::ROUND_NUMBER_BELOW:
                   $es_record['_source'][$then_field] = floor($es_record['_source'][$then_field]);
               break;
           }
        }

        return $es_record;

    }

    public function getRuleType()
    {
        return RuleConditions::THEN_ROUND_NUMBER;
    }



}