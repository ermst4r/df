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

namespace App\DfCore\DfBs\Import\Mapping;
use App\DfCore\DfBs\Enum\ImportType;
use \App\DfCore\DfBs\Import\Csv\CsvMappingFacade;
use \App\DfCore\DfBs\Import\Xml\XmlMappingFacade;


/**
 * Let us instantiate a specific file
 * Class CsvMapping
 * @package App\DfCore\DfBs\Import\Csv
 */
class MappingFactory  {

    public static function setMapping($file_name,$feed)
    {

        switch ($feed->feed_type) {
            case ImportType::CSV:
            case ImportType::TXT:
                $Csvmapping = new CsvMappingFacade($file_name);
                return [
                    'workable_data' => $Csvmapping->showCsvMapping()
                ];

            break;

            case ImportType::XML:
                // return us the root node
                $XmlFacade = new XmlMappingFacade($file_name,$feed->xml_root_node,$feed->prepend_nodes,$feed->prepend_identifier);
                /**
                 * TODO create custom feed parser
                 */

                $xml_mapping  =  $XmlFacade->showXmlMapping();
                return [
                    'workable_data'=>$xml_mapping['get_leafs'],
                    'root_node'=>$xml_mapping['root_node']
                ];
            break;



            default :
                throw new \Exception("No mapping type has been set");
        }
    }


    public static function IsNullOrEmptyString($question){
        return (!isset($question) || trim($question)==='');
    }


}