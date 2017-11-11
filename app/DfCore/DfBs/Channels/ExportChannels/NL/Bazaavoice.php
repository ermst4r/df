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
class Bazaavoice extends AbstractChannel
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
     * @param $results
     * @param $mapping_template
     * @param array $custom_fields
     * @param string $repeating_node
     * @param array $append_childs
     * @return mixed
     */
    protected function buildFeed($results, $mapping_template, $custom_fields = [], $repeating_node = 'products', $append_childs = [],$root_node=null)
    {


        $feed = new \SimpleXMLElement('<'.$this->root_node.' xmlns="http://www.bazaarvoice.com/xs/PRR/ProductFeed/5.6" name="beslist" 
        incremental="false" extractDate="'.gmdate(DATE_ATOM,time()).'"></'.$this->root_node.'>');
        $products = $feed->addChild('Products');




        if(count($results) > 0 ) {
            $brands = [];
            $categories = [];
            /**
             * first save the brands and categories
             */
            foreach ($results as $generated_id => $source) {
                foreach ($this->mapping_template as $mapping_value) {
                    if (isset($source['_source'][$mapping_value->channel_field_name])) {
                        if($mapping_value->channel_field_name == 'Brand') {
                            $brands[] =  $source['_source'][$mapping_value->channel_field_name];
                        }

                        if($mapping_value->channel_field_name == 'Category') {
                            $categories[] =  $source['_source'][$mapping_value->channel_field_name];
                        }
                    }
                }
            }

            $categories = array_unique($categories);
            $brands = array_unique($brands);


            /**
             * Set brands node
             */
            $brand_ids = [];
            if(count($brands) > 0 ) {
                $brands_node = $feed->addChild("Brands");
                $brand_counter = 1;
                foreach ($brands as $b) {
                    $brand_node = $brands_node->addChild('Brand');
                    $brand_node->addChild('ExternalId', $brand_counter);
                    $brand_node->addChild('Name', $b);
                    $brand_ids[$b] = $brand_counter;
                    $brand_counter++;
                }
            }

            /**
             * Set categories node
             */

            $cat_ids =[];
            if(count($categories) > 0 ) {
                $categories_node = $feed->addChild("Categories");
                $category_counter = 1;
                foreach ($categories as $c) {
                    $category_node = $categories_node->addChild('Category');
                    $category_node->addChild('ExternalId', $category_counter);
                    $category_node->addChild('Name', $c);
                    $cat_ids[$c] = $category_counter;
                    $category_counter++;
                }
            }


            foreach ($results as $generated_id => $source) {
                $product = $products->addChild('Product');
                foreach ($this->mapping_template as $mapping_value) {


                    /**
                     * Brand id
                     */
                    if($mapping_value->channel_field_name == 'Brand') {
                        $brand_search = array_search($source['_source'][$mapping_value->channel_field_name],$brands);
                        if($brand_search !== false ){
                            if(isset($brand_ids[$brands[$brand_search]])) {
                                $product->addChild('BrandExternalId',$brand_ids[$brands[$brand_search]]);
                            }
                        }
                        continue;
                    }


                    /**
                     * Category id
                     */

                    if($mapping_value->channel_field_name == 'Category' ) {
                        $category_search = array_search($source['_source'][$mapping_value->channel_field_name],$cat_ids);
                        if(isset($cat_ids[$categories[$category_search]])) {
                            $product->addChild('CategoryExternalId',$cat_ids[$categories[$category_search]]);
                        }
                        continue;
                    }

                    if (isset($source['_source'][$mapping_value->channel_field_name])) {

                            $field_value = $this->sanitizeSimpleXmlNodes($source['_source'][$mapping_value->channel_field_name]);
                            switch($mapping_value->channel_field_name ) {
                                case 'EAN':
                                    $eans = $product->addChild('EANs');
                                    $eans->addChild('EAN',$field_value);
                                break;

                                case 'ISBN':
                                    $isbn = $product->addChild('ISBNs');
                                    $isbn->addChild('ISBN',$field_value);
                                break;

                                case 'ManufacturerPartNumber':
                                    $ManufacturerPartNumber = $product->addChild('ManufacturerPartNumbers');
                                    $ManufacturerPartNumber->addChild('ManufacturerPartNumber',$field_value);
                                break;

                                case 'ModelNumber':
                                    $ModelNumbers = $product->addChild('ModelNumbers');
                                    $ModelNumbers->addChild('ModelNumber',$field_value);
                                break;

                                case 'UPC':
                                    $UPCS = $product->addChild('UPCs');
                                    $UPCS->addChild('UPC',$field_value);
                                break;

                                default:

                                        $product->addChild($mapping_value->channel_field_name, $field_value);

                            }


                    }
                }

                /**
                 * Custom velden toevoegen..
                 */
                if(count($custom_fields) > 0) {
                    $fields = $product->addChild('Attributes');
                    foreach($custom_fields as $custom_field) {
                        $field = $fields->addChild('Attribute');
                        $field->addAttribute("id",$custom_field->field_name);
                        if(isset($source['_source'][$custom_field->field_name])) {
                            $field->addAttribute('name',$custom_field->custom_field_name);
                            $field->addAttribute('value',$this->sanitizeSimpleXmlNodes($source['_source'][$custom_field->field_name]));
                        }
                    }
                }

            }




        }


       return $feed->asXML();
    }

    /**
     * @return string
     */
    public function buildChannel()
    {
        return  $this->buildFeed($this->feed_data,$this->mapping_template, $this->custom_fields,'Feed');

    }







}