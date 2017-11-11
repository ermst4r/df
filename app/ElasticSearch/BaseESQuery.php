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



namespace App\ElasticSearch;

use App\DfCore\DfBs\Enum\ConditionSelector;
use App\DfCore\DfBs\Enum\ESImportType;
use App\DfCore\DfBs\Enum\LogStates;
use App\DfCore\DfBs\Import\Mapping\DetectFieldType;
use App\DfCore\DfBs\Log\DfbuilderLogger;
use App\DfCore\DfBs\Log\LoggerFacade;
use Elasticsearch;
use function MongoDB\is_string_array;

/**
 * Abstract class with common ES commands
 * Class BaseElasticSearch
 * @package App\Entity\ElasticSearch
 */
abstract class BaseESQuery
{


    /**
     * @param $term
     * @param $field
     * @return array
     */
    public function buildContainQuery($term,$field)
    {

        return [
            'multi_match' => [
                'query'=>$term,
                'operator'=>'and',
                'fields' => $field
            ]
        ] ;
    }


    /**
     * @param $field
     * @param $term
     * @return array
     */
    public function buildEqualsQuery($field,$term)
    {
        return


                [
                    'match' => [
                        $field.'.keyword'=>$term
                    ]
                ];


    }


    /**
     * @param $field
     * @return array
     */
    public function buildEmptyQuery($field)
    {

       return
           [
               'match' => [
                   $field.'.keyword'=>""
               ]
           ];



    }


    /**
     * @param $term
     * @param $field
     */
    public function buildContainsMultiQuery($term,$field)
    {
        return  [
            'multi_match' => [
                'query'=>$term,
                'operator'=>'and',
                'fields' => $field
            ]
        ];
    }


    /**
     * @param $field
     * @param $term
     * @param $operator
     * @return array
     */
    public function buildRangeQuery($field,$term,$operator)
    {
      return  [
            $field => [$operator=>$term]
        ];

    }




}