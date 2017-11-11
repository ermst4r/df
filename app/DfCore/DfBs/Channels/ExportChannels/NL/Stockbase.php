<?php
namespace App\DfCore\DfBs\Channels\ExportChannels\NL;
use App\DfCore\DfBs\Channels\ExportChannels\Contract\AbstractChannel;
use App\DfCore\DfBs\Channels\ExportChannels\Contract\iExportChannel;

use App\Entity\ChannelFeed;
use App\Entity\Repository\ChannelFeedRepository;


/**
 *
 * Class TradeDoubler
 * @package App\DfCore\DfBs\Channels\ExportChannels\NL
 */
class Stockbase extends AbstractChannel
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
    private $feed_data;


    /**
     * @var \Illuminate\Database\Eloquent\Collection|static[]
     */
    private $channel_feed;
    /**
     * @var
     */
    private $channel_feed_id ;


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
        $this->channel_feed_id = $channel_feed_id;
        $this->feed_data = $feed_data;
        $this->custom_fields = $custom_fields;

    }





    /**
     * @return mixed
     */
    public function buildChannel()
    {
        return  $this->buildCsvFeed($this->feed_data,$this->mapping_template, $this->custom_fields,'item');

    }







}