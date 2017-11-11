<?php
namespace App\DfCore\DfBs\Channels\ExportChannels\NL;
use App\DfCore\DfBs\Channels\ExportChannels\Contract\AbstractChannel;
use App\DfCore\DfBs\Channels\ExportChannels\Contract\iExportChannel;
use App\ElasticSearch\ESChannel;

/**
 *
 * Class Adcrowd
 * @package App\DfCore\DfBs\Channels\ExportChannels\NL
 */
class Adcrowd extends AbstractChannel
    implements iExportChannel
{


    /**
     * @var
     */
    private $mapping_template;



    /**
     * @var
     */
    private $root_node = 'rss';


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
        $newsXML->addAttribute('version','2.0');
        $channel = $newsXML->addChild('channel');
        if(count($results) > 0 ) {
            foreach ($results as $generated_id => $source) {
                $item = $channel->addChild('item');
                foreach ($this->mapping_template as $mapping_value) {
                    if (isset($source['_source'][$mapping_value->channel_field_name])) {

                        switch ($mapping_value->channel_field_name) {
                            case 'enclosure':
                                $enclosure = $item->addChild('enclosure');
                                $enclosure->addAttribute('url',$this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]));
                            break;
                            default:
                                $item->addChild($mapping_value->channel_field_name, $this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]));
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