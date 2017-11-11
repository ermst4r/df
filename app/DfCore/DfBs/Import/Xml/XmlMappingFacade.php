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

namespace App\DfCore\DfBs\Import\Xml;
use App\DfCore\DfBs\FileWriter\FeedWriter;
use App\DfCore\DfBs\Import\Mapping\MappingFactory;
use App\DfCore\DfBs\Log\LoggerFacade;
use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Stream;
use Prewk\XmlStringStreamer\Parser;

class XmlMappingFacade {

    protected $stream;
    protected $parser;
    protected $streamer;
    protected $root_node;
    protected $prepend_nodes;
    protected $prepend_identifier;
    protected $file_name;

    /**
     * Construct the files
     * XmlFacade constructor.
     * @param $file_name
     */
    public function __construct($file_name,$xml_root_node = null, $prepend_nodes = null, $prepend_identifier = null)
    {


        $this->stream = new Stream\File($file_name,1024);
        $this->parser = new Parser\StringWalker();
        if(!MappingFactory::IsNullOrEmptyString(($xml_root_node))) {

            $options = array(
                "checkShortClosing" => true,
                'uniqueNode'=>$xml_root_node
            );
            $this->parser = new Parser\UniqueNode($options);
            $this->streamer = new XmlStringStreamer($this->parser, $this->stream);


        } else {
            $this->streamer = \Prewk\XmlStringStreamer::createStringWalkerParser($file_name);
        }

        $this->prepend_nodes = $prepend_nodes;
        $this->prepend_identifier = $prepend_identifier;
        $this->file_name = $file_name;
    }


    /**
     * @return mixed
     */
    public function getRootNode()
    {
        return $this->root_node;
    }

    /**
     * @param mixed $root_node
     */
    public function setRootNode($root_node)
    {
        $this->root_node = $root_node;
    }


    /**
     * Format the xml data for parsing
     * @param $xml_data
     * @param $prepend_nodes
     * @return mixed
     */
    public function formatXmlData($xml_data,$prepend_nodes,$root_node)
    {


        if(!is_null($prepend_nodes)) {

            $array_nodes = explode('.',$prepend_nodes);
            if(isset($xml_data[$root_node])) {
                $xml_data = $xml_data[$root_node];
                foreach($array_nodes as $nodes) {
                    if(isset($xml_data[$nodes])) {
                        $xml_data = $xml_data[$nodes];
                    }
                }
            }
        }
        return $xml_data;

    }

    /**
     * This method will allow to perform an internal xml query.
     * @param $xml_mapping
     * @param $prepend_nodes
     * @param int $prepend_identifier
     * @return array
     */
    protected function formatXmlRow($xml_mapping,$prepend_nodes,$prepend_identifier=0)
    {


        if(!is_null($prepend_nodes)) {

            $prepend_id = (is_null($prepend_identifier) ? 0 : $prepend_identifier);
            $array_nodes = explode('.',$prepend_nodes);
            foreach($array_nodes as $nodes) {
                if(isset($xml_mapping[$nodes])) {
                    $xml_mapping = $xml_mapping[$nodes];
                }
            }
            $xml_mapping = (isset($xml_mapping[$prepend_id]) ? $xml_mapping[$prepend_id] : []);
        }


        return $xml_mapping;
    }


    /**
     *
     * Show us the xml mapping
     * @return array
     */
    public function showXmlMapping()
    {

        $feed_type = FeedWriter::detectFeedType($this->file_name);

        $xml_mapping = $this->getXmlRow(0,$feed_type);

        $root_node = $this->getRootNode();
        $xml_mapping = $this->formatXmlRow($xml_mapping,$this->prepend_nodes,$this->prepend_identifier);
       if(is_null($this->prepend_nodes)) {
           $get_leafs = Xml2Array::leafs($xml_mapping,$root_node);
       } else {
           $get_leafs = array_keys($xml_mapping);
       }


        return [
            'get_leafs'=>$get_leafs,
            'root_node'=>$root_node
        ];
    }


    /**
     * @param $node
     * @return mixed
     */
    public function showXmlMappingForCurrentXmlRow($node,$feed_args=null)
    {

        $detect_feed_type = $feed_args['detect_feed_type'];
        $lf = $this->loadSimpleXmlString($node,$detect_feed_type);
        $root_node = $lf->getName();
        $xmlArray = Xml2Array::parse($lf);
        if(!is_null($feed_args['prepend_nodes'])) {
            $leafs = array_keys($this->formatXmlRow($xmlArray[$root_node], $feed_args['prepend_nodes']));
        } else {
            $leafs =  Xml2Array::leafs($xmlArray[$root_node],$root_node);
        }
        $this->setRootNode($root_node);
        return $leafs;



    }

    /**
     * Stream the xml row line by line
     * @return mixed
     */
    public function getXmlRow($page= 0,$feed_type=[])
    {
        $xmlArray = null;
        $root_node = '';
        $counter = 0 ;
        while ($node = $this->streamer->getNode()) {

            $lf = $this->loadSimpleXmlString($node,$feed_type);


//            if(!$lf) {
//
//
//                break;
//            }

            $root_node = $lf->getName();
            $xmlArray = Xml2Array::parse($lf);
            if($counter == $page) {
                break;
            }
            $counter ++;
        }
        $this->setRootNode($root_node);

        return $xmlArray[$root_node];

    }

    /**
     * @return mixed
     */
    public function getStream()
    {
        return $this->stream;
    }


    /**
     * @param $node
     * @param array $feed_type
     * @return bool|\SimpleXMLElement
     */
    public function loadSimpleXmlString($node,$feed_type=[])
    {


        try {
            $namespace = '';
            if(count($feed_type) > 0) {
                if(isset($feed_type['namespace'])) {
                    $namespace = $feed_type['namespace'];
                }
            }

            if(!empty($namespace)) {
                return  simplexml_load_string(utf8_encode($node),'SimpleXMLElement',LIBXML_NOERROR,$feed_type['namespace']);
            } else {
                return  simplexml_load_string(utf8_encode($node));
            }

        } catch ( \Exception $e) {

            LoggerFacade::addAlert( debug_string('Error parsing xml file',$e));
            return false;
        }

    }








    /**
     * @return mixed
     */
    public function getParser()
    {
        return $this->parser;
    }



    /**
     * @return mixed
     */
    public function getStreamer()
    {
        return $this->streamer;
    }




}