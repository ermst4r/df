<?php


namespace App\DfCore\DfBs\Import\Facade;


use App\DfCore\DfBs\Enum\ESIndexTypes;
use App\ElasticSearch\DynamicFeedRepository;
use App\Entity\Feed;
use App\Entity\Repository\FeedRepository;

class RemoveFeedFacade extends FeedRepository
{

    public function __construct(Feed $feed)
    {
        parent::__construct($feed);
    }

    /**
     * Delete the feed with the corresponding indexes.
     * @param $feed_id
     */
    public  function removeCompleteFeed($feed_id)
    {
        $feed = self::getFeed($feed_id);
        $filename  = DOWNLOAD_FOLDER.'/'.$feed->feed_type.'/'.$feed_id.'.'.$feed->feed_type;
        if(file_exists($filename)) {
            unlink($filename);
        }
        self::removeFeed($feed_id);
       $DynamicFeed = new DynamicFeedRepository(createEsIndexName($feed_id,ESIndexTypes::TMP),DFBUILDER_ES_TYPE);
       $DynamicFeed->deleteIndex();

       /**
        * @TODO Loop through channels and delete all channels
        *
        */

    }






}