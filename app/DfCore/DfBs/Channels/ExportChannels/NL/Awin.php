<?php
namespace App\DfCore\DfBs\Channels\ExportChannels\NL;
use App\DfCore\DfBs\Channels\ExportChannels\Contract\AbstractChannel;
use App\DfCore\DfBs\Channels\ExportChannels\Contract\iExportChannel;
use App\DfCore\DfBs\Import\Mapping\MappingValidator;
use App\ElasticSearch\ESChannel;

/**
 *
 * Class Adcrowd
 * @package App\DfCore\DfBs\Channels\ExportChannels\NL
 */
class Awin extends AbstractChannel
    implements iExportChannel
{


    /**
     * @var
     */
    private $mapping_template;



    /**
     * @var
     */
    private $root_node = 'products';


    /**
     * @var
     */
    private $custom_fields ;

    /**
     * @var
     */
    private $feed_data;


    /**
     * Adcrowd constructor.
     * @param $feed_data
     * @param $mapping_template
     * @param $cusom_fields
     */
    public function __construct( $feed_data, $mapping_template, $cusom_fields,$channel_feed_id )
    {
        $this->mapping_template = $mapping_template;
        $this->feed_data = $feed_data;
        $this->custom_fields = $cusom_fields;

    }



    /**
     * @param $mapping_template
     */
    protected function detectedShipping($mapping_template)
    {
        $found = false;
        foreach($mapping_template as $m) {

            switch($m->channel_field_name){
                case 'price-actualp':
                case 'productPriceold':
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

        $newsXML = new \SimpleXMLElement('<'.$this->root_node.'></'.$this->root_node.'>');
        $channel = $newsXML->addChild($repeating_node);
        if(count($results) > 0 ) {
            foreach ($results as $generated_id => $source) {
                $item = $channel->addChild('item');
                $price =null;
                if($this->detectedShipping($mapping_template)) {
                    $price = $item->addChild('price');
                }

                foreach ($this->mapping_template as $mapping_value) {
                    if (isset($source['_source'][$mapping_value->channel_field_name])) {
                        switch ($mapping_value->channel_field_name) {
                            case 'price-actualp':
                                $price->addChild('actualp',$this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]));
                            break;

                            case 'price-productPriceold':
                                $price->addChild('productPriceold',$this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]));
                            break;
                            default:
                                $item->addChild($mapping_value->channel_field_name, $this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]));
                        }


                    }
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
        return $newsXML->asXML();
    }

    /**
     * @return string
     */
    public function buildChannel()
    {
        return  $this->buildFeed($this->feed_data,$this->mapping_template, $this->custom_fields,'product');

    }







}