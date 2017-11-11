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
  | Calculate a number from another field
  |--------------------------------------------------------------------------
  */
namespace App\DfCore\DfBs\Rules\RuleStrategy\Strategies;


use App\DfCore\DfBs\Enum\CommonRulesEnum;
use App\DfCore\DfBs\Enum\RuleConditions;

class CalculateNumberStrategy implements iContract
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

        if(isset($es_record['_source'][$then_field])) {
            $calculate_field  = (isset($then_field_values[0]) ? $then_field_values[0] : '');
            $calculate_action  = (isset($then_field_values[1]) ? $then_field_values[1] : '');
            $calculate_method  = (isset($then_field_values[2]) ? $then_field_values[2] : 0);
            $calculate_field_2  = (isset($then_field_values[3]) ? $then_field_values[3] : 0);

            switch($calculate_action) {
                /**
                 *
                 */
                case CommonRulesEnum::DIVIDE_BY:
                    if(isset($es_record['_source'][$calculate_field] )) {
                        $es_record['_source'][$then_field] = number_format($es_record['_source'][$calculate_field] / $calculate_method,2,'.','');
                    }

                break;

                /**
                 *
                 */
                case CommonRulesEnum::MULTIPLY:
                    if(isset($es_record['_source'][$calculate_field] )) {
                        $es_record['_source'][$then_field] = number_format($es_record['_source'][$calculate_field] * $calculate_method,2,'.','');
                    }
                break;

                /**
                 *
                 */
                case CommonRulesEnum::PLUS:
                    if(isset($es_record['_source'][$calculate_field] )) {

                        $es_record['_source'][$then_field] = number_format($es_record['_source'][$calculate_field] + $calculate_method,2,'.','');

                    }
                break;

                /**
                 *
                 */
                case CommonRulesEnum::MINUS:
                    if(isset($es_record['_source'][$calculate_field] )) {
                        $es_record['_source'][$then_field] = number_format($es_record['_source'][$calculate_field] - $calculate_method,2,'.','');
                    }
                break;

                /**
                 *
                 */
                case CommonRulesEnum::DIVIDE_BY_FIELD:
                    if(isset($es_record['_source'][$calculate_field_2] )) {
                        $es_record['_source'][$then_field]  =    number_format($es_record['_source'][$then_field]  / $es_record['_source'][$calculate_field_2],2,'.','');
                    }
                break;

                /**
                 *
                 */
                case CommonRulesEnum::MULTIPLY_BY_FIELD:
                    if(isset($es_record['_source'][$calculate_field_2] )) {
                        $es_record['_source'][$then_field]  =    number_format($es_record['_source'][$then_field]  * $es_record['_source'][$calculate_field_2],2,'.','');
                    }
                break;


                case CommonRulesEnum::PLUS_FIELD:
                    if(isset($es_record['_source'][$calculate_field_2] )) {
                        $es_record['_source'][$then_field]  =    number_format($es_record['_source'][$then_field]  + $es_record['_source'][$calculate_field_2],2,'.','');
                    }
                break;

                case CommonRulesEnum::MINUS_FIELD:
                    if(isset($es_record['_source'][$calculate_field_2] )) {
                        $es_record['_source'][$then_field]  =    number_format($es_record['_source'][$then_field]  - $es_record['_source'][$calculate_field_2],2,'.','');
                    }
                break;



            }
        }
        return $es_record;
    }




    /**
     * @return int
     */
    public function getRuleType()
    {
        return RuleConditions::THEN_CALCULATE_NUMBER;
    }


}