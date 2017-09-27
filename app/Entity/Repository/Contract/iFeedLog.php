<?php
namespace App\Entity\Repository\Contract;

interface iFeedLog {



    public function getLogs($feed_id);
    public function createFeedLog($data = array(),$id = 0);
    public function getFeedLogs($start_date='',$end_date='',$limit = 0);

}