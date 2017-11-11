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
class AddRoll extends AbstractChannel
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
    private $root_node = 'rss';


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

        $feedwriter = new FeedWriter();
        $filename = $feedwriter->generateFileName($this->channel_feed->id,'xml');
        $rss = new \SimpleXMLElement('<'.$this->root_node.' version="2.0" xmlns:g="http://base.google.com/ns/1.0" xmlns:c="http://base.google.com/cns/1.0"></'.$this->root_node.'>');
        $channel = $rss->addChild('channel');
        $channel->addChild('title',$this->channel_feed->name);
        $channel->addChild('description',$this->channel_feed->name);
        $channel->addChild('pubDate',date("D M j G:i:s"));
        $channel->addChild('link',DFBUILDER_MAIN_WEBSITE_URL.DFBUILDER_CHANNEL_FOLDER.'/'.$filename);





        if(count($results) > 0 ) {
            foreach($results as $generated_id=>$source) {
                $item = $channel->addChild($repeating_node);
                $shipping = null;

                if($this->detectedShipping($mapping_template)) {
                    $shipping = $item->addChild('shipping',null,'http://base.google.com/ns/1.0');
                }

                foreach ($mapping_template as $mapping_value) {
                    var_dump($mapping_value->channel_field_name);
                    switch ($mapping_value->channel_field_name) {



                        case "g:shipping-service":
                        case "g:shipping-country":
                        case "g:shipping-price":
                            if(!is_null($shipping)) {
                                $shipping->addChild(str_replace('g:shipping-','',$mapping_value->channel_field_name),$this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]),'http://base.google.com/ns/1.0');
                            }


                        break;

                        case 'link':
                        case 'title':
                            $item->addChild($mapping_value->channel_field_name,$this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]));
                        break;



                        default:
                            $item->addChild($mapping_value->channel_field_name,$this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]),'http://base.google.com/ns/1.0');
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

        return $rss->asXML();

    }


    /**
     * @return mixed
     */
    public function buildChannel()
    {
        return  $this->buildFeed($this->feed_data,$this->mapping_template, $this->custom_fields,'item');

    }







}