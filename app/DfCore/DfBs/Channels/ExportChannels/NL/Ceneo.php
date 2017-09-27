<?php
namespace App\DfCore\DfBs\Channels\ExportChannels\NL;
use App\DfCore\DfBs\Channels\ExportChannels\Contract\AbstractChannel;
use App\DfCore\DfBs\Channels\ExportChannels\Contract\iExportChannel;
use App\DfCore\DfBs\Import\Mapping\MappingValidator;
use App\ElasticSearch\ESChannel;

/**
 *
 * Class Zanox
 * @package App\DfCore\DfBs\Channels\ExportChannels\NL
 */
class Ceneo extends AbstractChannel
    implements iExportChannel
{


    /**
     * @var
     */
    private $mapping_template;

    /**
     * @var
     */
    private $custom_fields ;



    /**
     * @var
     */
    private $root_node = 'offers';


    /**
     * @var
     */
    private $feed_data;


    /**
     * Zanox constructor.
     * @param $index_name
     * @param $type_name
     * @param $mapping_template
     */
    public function __construct( $feed_data, $mapping_template, $custom_fields,$channel_feed_id)
    {
        $this->mapping_template = $mapping_template;
        $this->feed_data = $feed_data;
        $this->custom_fields = $custom_fields;

    }

    private function detectImage($mapping_template)
    {
        $found = false;
        foreach($mapping_template as $m) {

            switch($m->channel_field_name){
                case 'additional_image_link':
                case 'image_link':
                    $found = true;
                    break;
                    break;

            }
        }
        return $found;
    }



    /**
     * @param $results
     * @param $mapping_template
     * @param array $custom_fields
     * @param string $repeating_node
     * @param array $append_childs
     * @return mixed
     */
    protected function buildFeed($results, $mapping_template, $custom_fields = [], $repeating_node = 'products', $append_childs = [],$root_node=null)
    {


        $offers = new \SimpleXMLElement('<'.$this->root_node.' version="2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:c="http://base.google.com/cns/1.0"></'.$this->root_node.'>');

        if(count($results) > 0 ) {
            foreach ($results as $generated_id => $source) {
                $imgs = null;
                if($this->detectImage($mapping_template)) {
                    $imgs = $offers->addChild('imgs');
                }
                $o = $offers->addChild($repeating_node);
                foreach ($mapping_template as $mapping_value) {
                    if($mapping_value->channel_field_name == 'id') {
                        $o->addAttribute('id',$source['_source'][$mapping_value->channel_field_name]);
                    }
                    if($mapping_value->channel_field_name == 'url') {
                        $o->addAttribute('url',$source['_source'][$mapping_value->channel_field_name]);
                    }

                    if($mapping_value->channel_field_name == 'price') {
                        $o->addAttribute('price',$source['_source'][$mapping_value->channel_field_name]);
                    }


                    if($mapping_value->channel_field_name == 'avail') {
                        $o->addAttribute('avail',$source['_source'][$mapping_value->channel_field_name]);
                    }

                    if($mapping_value->channel_field_name == 'set') {
                        $o->addAttribute('set',$source['_source'][$mapping_value->channel_field_name]);
                    }

                    if($mapping_value->channel_field_name == 'weight') {
                        $o->addAttribute('weight',$source['_source'][$mapping_value->channel_field_name]);
                    }

                    if($mapping_value->channel_field_name == 'basket') {
                        $o->addAttribute('basket',$source['_source'][$mapping_value->channel_field_name]);
                    }

                    if($mapping_value->channel_field_name == 'stock') {
                        $o->addAttribute('stock',$source['_source'][$mapping_value->channel_field_name]);
                    }

                    if($mapping_value->channel_field_name == 'cat') {
                        $o->addChild('cat','<![CDATA['.$source['_source'][$mapping_value->channel_field_name].']]>');
                    }

                    if($mapping_value->channel_field_name == 'name') {
                        $o->addChild('name','<![CDATA['.$source['_source'][$mapping_value->channel_field_name].']]>');
                    }


                    if($mapping_value->channel_field_name == 'desc') {
                        $o->addChild('desc','<![CDATA['.$source['_source'][$mapping_value->channel_field_name].']]>');
                    }

                    if($mapping_value->channel_field_name == 'image_link') {
                       $main = $imgs->addChild('main');
                       $i = $imgs->addChild('i');
                       $main->addAttribute('url',$source['_source'][$mapping_value->channel_field_name]);
                       $i->addAttribute('url',$source['_source'][$mapping_value->channel_field_name]);
                    }

                    if($mapping_value->channel_field_name == 'additional_image_link') {
                        $i = $imgs->addChild('i');
                        $i->addAttribute('url',$source['_source'][$mapping_value->channel_field_name]);
                    }
                }

            }
        }



        return $offers->asXML();

    }




    /**
     * Build the channel
     */
    public function buildChannel()
    {
        return $this->buildFeed($this->feed_data,$this->mapping_template, $this->custom_fields,'o',[],$this->root_node);

    }







}