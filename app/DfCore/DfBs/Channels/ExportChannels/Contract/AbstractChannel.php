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


namespace App\DfCore\DfBs\Channels\ExportChannels\Contract;


use App\DfCore\DfBs\Import\Mapping\MappingValidator;
use Sabre\Xml\Element\Cdata;
use Sabre\Xml\Service;
use League\Csv\Writer;
use League\Csv\Reader;

abstract  class AbstractChannel
{
    /**
     * We use sabre xml here, if we have a plain simple xml format
     * Otherwise we have to use a own custom parser
     * @param $products
     * @return string
     */
    protected function buildXml($root_node,$products)
    {
        $service = new Service();

        return $service->write( $root_node,
            [
                $products
            ]
        );
    }


    /**
     * Simple xml can throw an error if the format is wrongly mapped..
     * So let's sanitize the input
     * @param $value
     * @return string
     */
    protected function sanitizeSimpleXmlNodes($value)
    {
        return htmlspecialchars($value);
    }
    /**
     * A generic function to transform the mapped values to an array
     * So we can convert it to xml.
     * If you need complicated xml converting, please override this method
     * @param $results
     * @param $mapping_template
     * @param array $custom_fields
     * @param string $repeating_node
     * @param append_childs ['category'=>['field_name'=>'category','field_name2'=>'category']] . Where the array value will be transformed to a value..
     * @return array
     */
    protected function buildFeed($results, $mapping_template, $custom_fields = [], $repeating_node = 'products', $append_childs = [],$root_node='')
    {
        $producten = new \SimpleXMLElement('<'.$root_node.'></'.$root_node.'>');


        if(count($results) > 0 ) {
            foreach($results as $generated_id=>$source) {
                $item = $producten->addChild($repeating_node);
                foreach ($mapping_template as $mapping_value) {

                    $item->addChild($mapping_value->channel_field_name,$this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]));
                }

                if(count($custom_fields) > 0) {
                    foreach($custom_fields as $custom_field) {
                        if(isset($source['_source'][$custom_field->field_name])) {
                            $item->addChild(MappingValidator::formatMapping($custom_field->custom_field_name),$this->sanitizeSimpleXmlNodes($source['_source'][$custom_field->field_name]));
                        }
                    }
                }

            }
        }
        return $producten->asXML();
    }


    /**
     * @param $results
     * @param $mapping_template
     * @param array $custom_fields
     * @param string $repeating_node
     * @param array $append_childs
     * @return mixed
     */
    protected function buildCsvFeed($results, $mapping_template, $custom_fields = [], $repeating_node = 'products', $append_childs = [],$root_node=null)
    {
        $header = [];
        $contents = [];
        $writer = Writer::createFromFileObject(new \SplTempFileObject());
        $writer->setDelimiter(";");
        $writer->setNewline("\r\n");
        $writer->setOutputBOM(Reader::BOM_UTF8);
        foreach ($mapping_template as $mapping_value) {
            $header[] = $mapping_value->channel_field_name;
        }

        if(count($results) > 0 ) {
            $counter =0;
            foreach($results as $generated_id=>$source) {
                foreach ($header as $key=> $h) {
                    $contents[$counter][] = $source['_source'][$h];

                }
                $counter++;

            }
        }
        $writer->insertOne($header);
        $writer->insertAll($contents);
        $writer->setOutputBOM(Writer::BOM_UTF8); //adding the BOM sequence on output
        return $writer->__toString();


    }





}