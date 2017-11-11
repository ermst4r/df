<?php
namespace App\Entity\Repository;


use App\Entity\Repository\Contract\iStore;
use App\Entity\Repository\Contract\iTaskLog;
use App\Entity\Store;
use App\Entity\Tasklog;
use DB;

class TasklogRepository extends Repository  implements iTaskLog  {





    /**
     * @param array $data
     * @param int $id
     * @return $this|bool|\Illuminate\Database\Eloquent\Model
     */
    public function createTask($data = array(),$id=0)
    {
        if($id == 0 ) {
            return $this->model->create($data);
        } else {
            return $this->model->where('id',$id)->update($data);
        }

    }

    /**
     * @param $limit
     * @return \Illuminate\Support\Collection
     */
    public function getTasks($limit,$status=true)
    {
        return $this->model->where('status',$status)->orderBy('created_at','desc')
            ->limit($limit)
            ->get();

    }


    /**
     * @param $feed_id
     * @param $status
     * @return \Illuminate\Support\Collection
     */
    public function getTaskByFeed($feed_id,$status=false)
    {
        return $this->model->where('fk_feed_id',$feed_id)
            ->where('status',$status)
            ->orderBy('created_at','desc')
            ->get();
    }


    /**
     * @param string $start_date
     * @param string $end_date
     * @param int $limit
     * @return mixed
     */
    public function getTaskLogs($start_date='',$end_date='',$limit=0)
    {
        if($start_date == '' && $end_date == '') {
            $start_date = date('Y-m-d 00:00:00');
            $end_date = date('Y-m-d 23:59:59');
        } else {
            $start_date = $start_date. ' 00:00:00';
            $end_date = $end_date. ' 23:59:59';
        }


        if($limit == 0 ) {
            return $this->model
                ->join('feeds', $this->model->getTable().'.fk_feed_id', '=', 'feeds.id')
                ->select(DB::RAW('feeds.feed_name AS feed_name,'.$this->model->getTable().'.task AS task, '.$this->model->getTable().'.status AS status, '.$this->model->getTable().'.created_at AS created_at'))
                ->whereBetween($this->model->getTable().'.created_at',array($start_date,$end_date))
                ->orderBy($this->model->getTable().'.created_at','desc')
                ->get();
        } else {
            return $this->model
                ->join('feeds', $this->model->getTable().'.fk_feed_id', '=', 'feeds.id')
                ->select(DB::RAW('feeds.feed_name AS feed_name,'.$this->model->getTable().'.task AS task, '.$this->model->getTable().'.status AS status, '.$this->model->getTable().'.created_at AS created_at'))
                ->whereBetween($this->model->getTable().'.created_at',array($start_date,$end_date))
                ->orderBy($this->model->getTable().'.created_at','desc')
                ->limit($limit)
                ->get();
        }

    }
}
?>