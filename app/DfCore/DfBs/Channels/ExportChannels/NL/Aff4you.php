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
class Aff4you extends AbstractChannel
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
    private $root_node = 'producten';


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

    protected function buildFeed($results, $mapping_template, $custom_fields = [], $repeating_node = 'products', $append_childs = [],$root_node=null)
    {
        $producten = new \SimpleXMLElement('<'.$this->root_node.'></'.$this->root_node.'>');


        if(count($results) > 0 ) {
            foreach($results as $generated_id=>$source) {
                $item = $producten->addChild($repeating_node);
                $shipping = null;



                foreach ($mapping_template as $mapping_value) {

                    $item->addChild($mapping_value->channel_field_name,'<![CDATA['.$this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]).']]>');

                }


                if(count($custom_fields) > 0) {

                    foreach($custom_fields as $custom_field) {
                        if(isset($source['_source'][$custom_field->field_name])) {
                            $item->addChild(MappingValidator::formatMapping($custom_field->custom_field_name),'<![CDATA['.$this->sanitizeSimpleXmlNodes($source['_source'][$custom_field->field_name]).']]>');
                        }
                    }
                }

            }
        }
        return $producten->asXML();
    }


    /**
     * Build the channel
     */
    public function buildChannel()
    {
        return $this->buildFeed($this->feed_data,$this->mapping_template, $this->custom_fields,'product',[],null);

    }







}