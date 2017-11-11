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
class Tobedressed extends AbstractChannel
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
    private function detectPhotos($mapping_template)
    {
        $found = false;
        foreach($mapping_template as $m) {

            switch($m->channel_field_name){
                case 'extra_images':
                case 'images':
                    $found = true;
                    break;
                    break;

            }
        }
        return $found;
    }

    public function detectAdditional($mapping_template)
    {
        $found = false;
        foreach($mapping_template as $m) {

            switch($m->channel_field_name){
                case 'materiaal':
                case 'pasvorm':

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

        $products = new \SimpleXMLElement('<'.$this->root_node.' ></'.$this->root_node.'>');




        if(count($results) > 0 ) {
            foreach($results as $generated_id=>$source) {
                $item = $products->addChild($repeating_node);
                $image = null;
                $size = null;

                if($this->detectPhotos($mapping_template)) {
                    $images = $item->addChild('images');


                }

                if($this->detectAdditional($mapping_template)) {
                    $additional = $item->addChild('additional',null);

                }

                foreach ($mapping_template as $mapping_value) {
                    switch ($mapping_value->channel_field_name) {
                        case 'images':
                            $image1 = $images->addChild('image',$this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]));
                            $image1->addAttribute('cover',0);
                        break;

                        case 'extra_images':
                            $image = $images->addChild('image',$this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]));
                            $image->addAttribute('cover',1);
                        break;



                        case 'materiaal':
                        case 'pasvorm':
                            $field = $additional->addChild('field');
                            $field->addAttribute('name',$mapping_value->channel_field_name);
                            $field->addAttribute('value',$this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]));
                        break;


                        default:

                            $item->addChild($mapping_value->channel_field_name, $this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]));
                    }
                }

                $item->addChild('created',date("Y-m-d H:i:s"));
                $item->addChild('modified',date("Y-m-d H:i:s"));
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