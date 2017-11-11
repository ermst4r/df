<?php
namespace App\DfCore\DfBs\Channels\ExportChannels\NL;
use App\DfCore\DfBs\Channels\ExportChannels\Contract\AbstractChannel;
use App\DfCore\DfBs\Channels\ExportChannels\Contract\iExportChannel;
use App\DfCore\DfBs\FileWriter\FeedWriter;
use App\DfCore\DfBs\Import\Mapping\MappingValidator;
use App\ElasticSearch\ESChannel;
use App\Entity\ChannelFeed;
use App\Entity\Repository\ChannelFeedRepository;

/**
 *
 * Class TradeDoubler
 * @package App\DfCore\DfBs\Channels\ExportChannels\NL
 */
class Sociomantic extends AbstractChannel
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
    private $root_node = 'products';


    /**
     * @var
     */
    private $feed_data;


    private $channel_feed;

    /**
     * Zanox constructor.
     * @param $index_name
     * @param $type_name
     * @param $mapping_template
     */
    public function __construct( $feed_data, $mapping_template, $custom_fields,$channel_feed_id)
    {
        $this->mapping_template = $mapping_template;
        $channel_feed_repository = new ChannelFeedRepository(new ChannelFeed());
        $this->channel_feed = $channel_feed_repository->getChannelFeed($channel_feed_id);

        $this->feed_data = $feed_data;
        $this->custom_fields = $custom_fields;

    }


    /**
     * @param $mapping_template
     */
    private function detectedShipping($mapping_template)
    {
        $found = false;
        foreach($mapping_template as $m) {

            switch($m->channel_field_name){
                case 'g:shipping-service':
                case 'g:shipping-price':
                case 'g:shipping-country':
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

        $products = new \SimpleXMLElement('<'.$this->root_node.' xmlns="http://data-vocabular.org/product/"></'.$this->root_node.'>');




        if(count($results) > 0 ) {
            foreach($results as $generated_id=>$source) {
                $item = $products->addChild($repeating_node);


                foreach ($mapping_template as $mapping_value) {
                    switch ($mapping_value->channel_field_name) {


                        case 'identifier':
                           $identifier = $item->addChild('identifier', $this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]));
                           $identifier->addAttribute('type','ean');
                        break;

                        case 'category':
                            $category = $item->addChild('category', $this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]));
                            $category->addAttribute('level','2');
                        break;

                        default:
                            $item->addChild($mapping_value->channel_field_name, $this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]));
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
        return $products->asXML();

    }


    /**
     * @return mixed
     */
    public function buildChannel()
    {
        return  $this->buildFeed($this->feed_data,$this->mapping_template, $this->custom_fields,'product');

    }







}