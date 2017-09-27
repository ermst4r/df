<?php
/**
 * Created by PhpStorm.
 * User: erm
 * Date: 08-02-17
 * Time: 20:07
 */

namespace App\Entity\Repository;


use App\Entity\FeedLog;
use App\Entity\Repository\Contract\iFeedLog;
use DB;
class FeedLogRepository implements iFeedLog
{

    /**
     * @var FeedLog
     */
    private $feed_log;

    /**
     * FeedLogRepository constructor.
     * @param FeedLog $feed_log
     */
    public function __construct(FeedLog $feed_log)
    {

        $this->feed_log = $feed_log;
    }


    /**
     * Get the feed by id
     * @param $id
     * @return mixed
     */
    public function getLogs($feed_id)
    {
        return $this->feed_log->where('fk_feed_id',$feed_id)->get();
    }

    /**
     * Add the feed to the database
     * @param array $data
     * @param int $id
     * @return int
     */
    public function createFeedLog($data = array(),$id = 0)
    {
        if($id == 0 ) {
            $feed_log = $this->feed_log->create($data);
            return $feed_log->id;
        } else {
            $this->feed_log->find($id)->update($data);
            return $id;
        }
    }

    /**
     * @param string $start_date
     * @param string $end_date
     * @return mixed
     */
    public function getFeedLogs($start_date='',$end_date='',$limit = 0)
    {
        if($start_date == '' && $end_date == '') {
            $start_date = date('Y-m-d 00:00:00');
            $end_date = date('Y-m-d 23:59:59');
        } else {
            $start_date = $start_date. ' 00:00:00';
            $end_date = $end_date. ' 23:59:59';
        }

        $table = $this->feed_log->getTable();
        if($limit == 0 ) {
            return DB::table($table)
                ->join('feeds', $table.'.fk_feed_id', '=', 'feeds.id')
                ->select(DB::RAW($table.'.created_at AS log_date,'.$table.'.*, feeds.*'))
                ->whereBetween($table.'.created_at',array($start_date,$end_date))
                ->orderBy($table.'.created_at','desc')
                ->get();
        } else {
            return DB::table($table)
                ->join('feeds', $table.'.fk_feed_id', '=', 'feeds.id')
                ->select(DB::RAW($table.'.created_at AS log_date,'.$table.'.*, feeds.*'))
                ->whereBetween($table.'.created_at',array($start_date,$end_date))
                ->orderBy($table.'.created_at','desc')
                ->limit($limit)
                ->get();
        }

    }







}