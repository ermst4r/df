<?php namespace App\DfCore\DfBs\Rules;
use App\DfCore\DfBs\Enum\ConditionSelector;
use App\DfCore\DfBs\Enum\RuleConditions;

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


class CustomControlerRegister
{

    public static function registerCustomControlForJavascript($rules_dictonary)
    {
        /**
         * Register rules for custom control
         */
        $register = [
            \App\DfCore\DfBs\Enum\RuleConditions::THEN_ALTER_FIELD_VALUE,
            \App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING,
            \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_AND_REPLACE,
            \App\DfCore\DfBs\Enum\RuleConditions::THEN_COPY_VALUE_FROM_FIELD,
            \App\DfCore\DfBs\Enum\RuleConditions::THEN_COMBINE_FEED_VALUE,
            \App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_REPLACE_OTHER_FIELD,
            \App\DfCore\DfBs\Enum\RuleConditions::THEN_APPEND_VALUE,



        ];


        $return_array = [];

        if(isset($rules_dictonary['rules']['then_action'])) {

            foreach($rules_dictonary['rules']['then_action'] as $key=>$rule_condition) {

            /**
             *  Not in the register..
             * Then skip the value
             */
             if(in_array($rule_condition,$register) == false) {
                 continue;
             }

              if(isset($rules_dictonary['rules']['then_field_values'][$key])) {
                  $items  = 0;
                  foreach($rules_dictonary['rules']['then_field_values'][$key] as $field_key=>$field_value) {
                      $return_array[] = $rule_condition.'_'.$key.'_'.$items;
                      $items++;
                  }
                }
            }
        }
        return $return_array;
    }


}