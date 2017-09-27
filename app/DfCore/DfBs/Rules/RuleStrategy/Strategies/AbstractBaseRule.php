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
namespace App\DfCore\DfBs\Rules\RuleStrategy\Strategies;

abstract class AbstractBaseRule {


    /**
     * Give us the feed rules
     * @param $es_record
     * @return array
     */
    public function formatFeedFields($es_record){

        $field_values = [];
        if(isset($es_record['_source'])) {
            foreach (array_keys($es_record['_source']) as $field) {
                $field_values['{'.$field.'}'] = (isset($es_record['_source'][$field])  && !is_array($es_record['_source'][$field])? $es_record['_source'][$field] : '') ;
            }
        }
        return $field_values;
    }




}