<?php
namespace App\DfCore\DfBs\Channels\ExportChannels\NL;
use App\DfCore\DfBs\Channels\ExportChannels\Contract\AbstractChannel;
use App\DfCore\DfBs\Channels\ExportChannels\Contract\iExportChannel;
use App\ElasticSearch\ESChannel;

/**
 *
 * Class TradeDoubler
 * @package App\DfCore\DfBs\Channels\ExportChannels\NL
 */
class TradeDoubler extends AbstractChannel
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
    private $root_node = 'productFeed';


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
         $newsXML->addAttribute('version', '2.0');
         if(count($results) > 0 ) {
             foreach($results as $generated_id=>$source) {
                 $product = $newsXML->addChild($repeating_node);

                 foreach($mapping_template as $mapping_value) {
                     if(isset($source['_source'][$mapping_value->channel_field_name])) {
                         switch ($mapping_value->channel_field_name) {
                             /**
                              * TD ID
                              */
                             case 'product_id':
                                 $product->addAttribute('id',$this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]));
                             break;
                             /**
                              * TD Category
                              */
                             case 'category':
                                 $categories = $product->addChild('categories');
                                 $category = $categories->addChild('category');
                                 $category->addAttribute('name', $this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]));

                             break;

                             default:
                                 $product->addChild($mapping_value->channel_field_name,$this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]));
                         }
                     }
                 }


                 /**
                  * Finally append the custom fields
                  */
                 if(count($custom_fields) > 0) {
                     $fields = $product->addChild('fields');
                     foreach($custom_fields as $custom_field) {
                         $field = $fields->addChild('field');
                         if(isset($source['_source'][$custom_field->field_name])) {
                             $field->addAttribute('name',$custom_field->custom_field_name);
                             $field->addAttribute('value',$this->sanitizeSimpleXmlNodes($source['_source'][$custom_field->field_name]));
                         }
                     }
                 }
             }
         }


         return $newsXML->asXML();

     }


    /**
     * @return mixed
     */
    public function buildChannel()
    {
        return  $this->buildFeed($this->feed_data,$this->mapping_template, $this->custom_fields,'product');

    }







}