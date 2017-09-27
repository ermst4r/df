<?php namespace App\DfCore\DfBs\Enum;
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

class RuleConditions
{

    /**
     * Then statements..
     */
    const THEN_APPEND_VALUE = 1;
    const THEN_FIND_AND_REPLACE = 2;
    const THEN_FIND_AND_REPLACE_FIELD_NAME = 3;
    const THEN_ALTER_FIELD_VALUE = 4;
    const THEN_COPY_VALUE_FROM_FIELD = 5;
    const THEN_COMBINE_FEED_VALUE = 6;
    const THEN_FIND_REPLACE_OTHER_FIELD = 7;
    const THEN_SPLIT_FIELD = 8;
    const THEN_STRING_LENGTH = 9;
    const THEN_COMMON_STRING_ACTIONS = 10;
    const THEN_GOOGLE_TRACKING = 11;
    const THEN_ROUND_NUMBER = 12;
    const THEN_CALCULATE_NUMBER = 13;
    const THEN_CALCULATE_SUM = 14;
    const THEN_CALCULATE_STRING_LENGTH = 15;


    /**
     * OR and AND operators
     */

    const OR_OPERATOR = 'or';
    const AND_OPERATOR = 'and';
    const START_OPERATOR = 'start';



}