<?php
namespace App\DfCore\DfBs\Import\Xml;

use App\DfCore\DfBs\Import\Mapping\MappingValidator;
use App\DfCore\DfBs\Log\LoggerFacade;

class XmlReaderFacade extends XmlMappingFacade
{
    /**
     * XmlReaderFacade constructor.
     * @param $file_name
     */
    public function __construct($file_name,$xml_root_node = '')
    {
        parent::__construct($file_name,$xml_root_node);
    }


    /**
     * @param $mapping
     * @return array
     */
    public function mappingToTransformableArray($mapping)
    {
        $mapping_to_array = explode('.',$mapping);
        $returnArray = [];
        if(count($mapping_to_array) >0 ) {
           foreach(array_values($mapping_to_array) as $per_value) {
               $returnArray[] = $per_value;
           }

        }
        return array_values($returnArray);



    }


    /**
     * When we parse the xml node, we stream it.
     * @return bool|string
     */
    public function streamingNode()
    {
        return $this->streamer->getNode();
    }


    /**
     * @param $node
     * @param null $feed_args
     * @return array
     */
    public function transformCustomXmlToArray($node,$feed_args=null)
    {


        $xml_node = $this->loadSimpleXmlString($node);
        $xml_data =   $this->formatXmlData(Xml2Array::parse($xml_node),$feed_args['prepend_nodes'],$feed_args['feed_xml_root_node']);
        return $xml_data;

    }


    /**
     * @param $row
     * @param null $feed_args
     * @return array
     */
    public function prepareCustomXml($row)
    {
        $return_data = [];
        foreach (array_keys($row) as $m) {
            $mapping_key = $this->mappingToTransformableArray($m);
            $tmp_storage = $row;
            $inner_counter = 0;
            foreach($mapping_key as $mapping_value) {
                $inner_counter ++;
                $tmp_storage = $tmp_storage[$mapping_value];
                // do a check to see if this is not an array
                // if thats the case remove..
                if(count($mapping_key) == $inner_counter) {
                    if(is_array($tmp_storage)) {
                        $tmp_storage = false;
                    }
                }
            }
            if($tmp_storage != false) {
                $return_data[MappingValidator::formatMapping($m)] = $tmp_storage;
            }
        }
        return $return_data;

    }


    /**
     * Convert the xml nodes for insert. They key will be the xml node in dotted format.
     * And the value will be the XML value. On this way we can insert the values into a database
     * @param $node
     * @return array
     */

    public function prepareXmlNodeForInsertIntoDatabase($node,$feed_args=null)
    {

        $mapping = $this->showXmlMappingForCurrentXmlRow($node,$feed_args);
        $detect_feed_type = $feed_args['detect_feed_type'];
        $xml_node = $this->loadSimpleXmlString($node,$detect_feed_type);

        $xml_data =   Xml2Array::parse($xml_node);
        $return_data = [];
        $tmp_xml_data =   null ;
        foreach ($mapping as $m) {
            $mapping_key = $this->mappingToTransformableArray($m);
            $tmp_storage = $xml_data;
            $inner_counter = 0;
            foreach($mapping_key as $mapping_value) {
                $inner_counter ++;
                $tmp_storage = $tmp_storage[$mapping_value];

                // do a check to see if this is not an array
                // if thats the case remove..
                if(count($mapping_key) == $inner_counter) {
                    if(is_array($tmp_storage)) {
                        $tmp_storage = false;
                    }
                }

            }
            if($tmp_storage != false) {
                $return_data[MappingValidator::formatMapping($m)] = $tmp_storage;
            }

        }
        return $return_data;
    }


    /**
     * Convert an xml array from the mapping to workable values
     * @param $plain_mapped
     * @param $xml_data
     * @return array
     */
    public function ConvertXmlMappedToData($plain_mapped,$xml_data)
    {

        $xml_data = Xml2Array::parse($this->loadSimpleXmlString($xml_data));
        $save = [];
        foreach($plain_mapped as $plain_mapped_key => $plain_mapped_value) {
            $mapping = self::mappingToTransformableArray($plain_mapped_key);
            $tmp_xml_data = $xml_data;

            foreach($mapping as $m) {
                // double check if no errs
                if(!isset($tmp_xml_data[$m])) {
                    continue;
                }
                $tmp_xml_data = $tmp_xml_data[$m];
            }
            // don't show us array values, only workable values
            // change to  $save[key($plain_mapped_value)] = $tmp_xml_data; if you want to use arrays
            if(!is_array($tmp_xml_data)) {
                $save[key($plain_mapped_value)] = $tmp_xml_data;
            }
        }

        return $save;
    }





}