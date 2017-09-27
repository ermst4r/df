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
class Ciao extends AbstractChannel
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
     * Build the channel
     */
    public function buildChannel()
    {
        return $this->buildFeed($this->feed_data,$this->mapping_template, $this->custom_fields,'product',[],$this->root_node);

    }







}