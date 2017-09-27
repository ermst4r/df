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


 class ConditionToHtmlFormType
 {


     /**
      * Apply the then form conditions...
      * @return array
      */
     public static function thenFormOptions()
     {

         return [
             ['value'=>'algemeen','type'=>'optgroup','text'=>trans('messages.rules_then_conditon_67')],
             ['value'=>\App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_AND_REPLACE,'type'=>'option','text'=>trans('messages.rules_then_conditon_1')],
             ['value'=>\App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_AND_REPLACE_FIELD_NAME,'type'=>'option','text'=>trans('messages.rules_then_conditon_2')],
             ['value'=>\App\DfCore\DfBs\Enum\RuleConditions::THEN_APPEND_VALUE,'type'=>'option','text'=>trans('messages.rules_then_conditon_3')],
             ['value'=>\App\DfCore\DfBs\Enum\RuleConditions::THEN_ALTER_FIELD_VALUE,'type'=>'option','text'=>trans('messages.rules_then_conditon_8')],
             ['value'=>\App\DfCore\DfBs\Enum\RuleConditions::THEN_COPY_VALUE_FROM_FIELD,'type'=>'option','text'=>trans('messages.rules_then_conditon_12')],
             ['value'=>\App\DfCore\DfBs\Enum\RuleConditions::THEN_COMBINE_FEED_VALUE,'type'=>'option','text'=>trans('messages.rules_then_conditon_15')],
             ['value'=>\App\DfCore\DfBs\Enum\RuleConditions::THEN_FIND_REPLACE_OTHER_FIELD,'type'=>'option','text'=>trans('messages.rules_then_conditon_19')],
             ['value'=>\App\DfCore\DfBs\Enum\RuleConditions::THEN_SPLIT_FIELD,'type'=>'option','text'=>trans('messages.rules_then_conditon_26')],
             ['value'=>\App\DfCore\DfBs\Enum\RuleConditions::THEN_STRING_LENGTH,'type'=>'option','text'=>trans('messages.rules_then_conditon_31')],
             ['value'=>\App\DfCore\DfBs\Enum\RuleConditions::THEN_COMMON_STRING_ACTIONS,'type'=>'option','text'=>trans('messages.rules_then_conditon_36')],
             ['value'=>\App\DfCore\DfBs\Enum\RuleConditions::THEN_GOOGLE_TRACKING,'type'=>'option','text'=>trans('messages.rules_then_conditon_44')],
             ['value'=>'algemeen','type'=>'optgroup','text'=>trans('messages.rules_then_conditon_68')],
             ['value'=>\App\DfCore\DfBs\Enum\RuleConditions::THEN_ROUND_NUMBER,'type'=>'option','text'=>trans('messages.rules_then_conditon_50')],
             ['value'=>\App\DfCore\DfBs\Enum\RuleConditions::THEN_CALCULATE_NUMBER,'type'=>'option','text'=>trans('messages.rules_then_conditon_56')],
             ['value'=>\App\DfCore\DfBs\Enum\RuleConditions::THEN_CALCULATE_SUM,'type'=>'option','text'=>trans('messages.rules_then_conditon_59')],
             ['value'=>\App\DfCore\DfBs\Enum\RuleConditions::THEN_CALCULATE_STRING_LENGTH,'type'=>'option','text'=>trans('messages.rules_then_conditon_62')],


         ];
     }


     /**
      * @return array
      */
     public static function ifFormOptions()
     {


         return [
             ['value'=>trans('messages.rules_if_lbl18'),'type'=>'optgroup','text'=>trans('messages.rules_if_lbl18')],
             ['value'=>\App\DfCore\DfBs\Enum\ConditionSelector::CONTAIN,'type'=>'option','text'=>trans('messages.rules_if_lbl4')],
             ['value'=>\App\DfCore\DfBs\Enum\ConditionSelector::NOT_CONTAIN,'type'=>'option','text'=>trans('messages.rules_if_lbl5')],
             ['value'=>\App\DfCore\DfBs\Enum\ConditionSelector::EQUALS,'type'=>'option','text'=>trans('messages.rules_if_lbl6')],
             ['value'=>\App\DfCore\DfBs\Enum\ConditionSelector::NOT_EQUALS,'type'=>'option','text'=>trans('messages.rules_if_lbl7')],
             ['value'=>\App\DfCore\DfBs\Enum\ConditionSelector::IS_EMPTY,'type'=>'option','text'=>trans('messages.rules_if_lbl8')],
             ['value'=>\App\DfCore\DfBs\Enum\ConditionSelector::IS_NOT_EMPTY,'type'=>'option','text'=>trans('messages.rules_if_lbl9')],
             ['value'=>trans('messages.rules_if_lbl19'),'type'=>'optgroup','text'=>trans('messages.rules_if_lbl19')],
             ['value'=>\App\DfCore\DfBs\Enum\ConditionSelector::CONTAINS_MULTI,'type'=>'option','text'=>trans('messages.rules_if_lbl10')],
             ['value'=>\App\DfCore\DfBs\Enum\ConditionSelector::NOT_CONTAINS_MULTI,'type'=>'option','text'=>trans('messages.rules_if_lbl11')],
             ['value'=>\App\DfCore\DfBs\Enum\ConditionSelector::EQUALS_MULTI,'type'=>'option','text'=>trans('messages.rules_if_lbl12')],
             ['value'=>\App\DfCore\DfBs\Enum\ConditionSelector::NOT_EQUALS_MULTI,'type'=>'option','text'=>trans('messages.rules_if_lbl13')],
             ['value'=>trans('messages.rules_if_lbl20'),'type'=>'optgroup','text'=>trans('messages.rules_if_lbl20')],
             ['value'=>\App\DfCore\DfBs\Enum\ConditionSelector::GT,'type'=>'option','text'=>trans('messages.rules_if_lbl14')],
             ['value'=>\App\DfCore\DfBs\Enum\ConditionSelector::GT_EQ,'type'=>'option','text'=>trans('messages.rules_if_lbl15')],
             ['value'=>\App\DfCore\DfBs\Enum\ConditionSelector::LT,'type'=>'option','text'=>trans('messages.rules_if_lbl16')],
             ['value'=>\App\DfCore\DfBs\Enum\ConditionSelector::LT_EQ,'type'=>'option','text'=>trans('messages.rules_if_lbl17')]

         ];

     }



     /**
      * @param $form_type
      * @return array
      */
        public static function ifToFormType($form_type)
        {

            switch($form_type) {

                case ConditionSelector::IS_EMPTY:
                case ConditionSelector::IS_NOT_EMPTY:
                    return ['type'=>'empty'];
                break;

                case ConditionSelector::CONTAIN:
                    return [
                        'type'=>'text',
                        'tooltip'=> 'Welke waarde mag in het veld voorkomen?',
                        'placeholder'=>'Bijv: Samsung'
                    ];
                break;
                case ConditionSelector::NOT_CONTAIN:
                    return [
                        'type'=>'text',
                        'tooltip'=> 'Welke waarde mag niet in het veld voorkomen',
                        'placeholder'=>'Bijv iPhone'
                    ];
                    break;

                case ConditionSelector::EQUALS:
                    return [
                        'type'=>'text no_listener',
                        'tooltip'=> 'Aan welke waarde moet het veld exact voldoen',
                        'placeholder'=>'Bijv schoen'
                    ];
                break;
                case ConditionSelector::NOT_EQUALS:
                    return [
                        'type'=>'text no_listener',
                        'tooltip'=> 'Aan welke waarde mag het veld niet  voldoen',
                        'placeholder'=>'Bijv schoen'
                    ];
                break;
                case ConditionSelector::GT:
                    return [
                        'type'=>'text',
                        'tooltip'=> 'Is groter dan x ',
                        'placeholder'=>'Bijv 10'
                    ];
                    break;
                case ConditionSelector::GT_EQ:
                    return [
                        'type'=>'text',
                        'tooltip'=> 'Is groter of gelijk aan x',
                        'placeholder'=>'Bijv: 10'
                    ];
                    break;
                case ConditionSelector::LT:
                    return [
                        'type'=>'text',
                        'tooltip'=> 'Is kleiner dan waarde x',
                        'placeholder'=>'Bijv 10'
                    ];
                    break;
                case ConditionSelector::LT_EQ:
                    return [
                        'type'=>'text',
                        'tooltip'=> 'Is kleiner of gelijk aan x',
                        'placeholder'=>'Bijv 10'
                    ];
                    break;

                break;
                case ConditionSelector::CONTAINS_MULTI:
                    return [
                        'type'=>'textarea',
                        'tooltip'=> 'Geef een waarde op per regel.',
                        'value'=>'waarde1\nwaarde2'
                    ];
                    break;

                case ConditionSelector::EQUALS_MULTI:
                    return [
                        'type'=>'textarea',
                        'tooltip'=> 'Geef een waarde op per regel',
                        'placeholder'=>'waarde1\nwaarde2'
                    ];
                break;
                case ConditionSelector::NOT_EQUALS_MULTI:
                    return [
                        'type'=>'textarea',
                        'tooltip'=> 'Geef een waarde op per regel',
                        'placeholder'=>'waarde1\nwaarde2'
                    ];
                break;
                case ConditionSelector::NOT_CONTAINS_MULTI:
                    return [
                        'type'=>'textarea',
                        'tooltip'=> 'Geef een waarde op per regel',
                        'placeholder'=>'waarde1\nwaarde2'
                    ];
                break;




            }
        }
 }