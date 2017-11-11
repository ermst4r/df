<?php
/**
 * Created by PhpStorm.
 * User: erm
 * Date: 08-02-17
 * Time: 20:07
 */

namespace App\Entity\Repository;


use App\Entity\CompositeMapping;
use App\Entity\Repository\Contract\iCompositeMapping;


/**
 * Class CompositeMappingRepository
 * @package App\Entity\Repository
 */
class CompositeMappingRepository  extends Repository implements iCompositeMapping
{

    private $composite_mapping;

    /**
     * @param $feed_id
     * @return bool
     */
    public function hasCompositeMapping($feed_id)
    {
        return $this->model->where('fk_feed_id',$feed_id)->count() > 0 ;
    }

    

    /**
     * @param array $data
     * @return mixed
     */
    public function createCompositeMapping($data = [])
    {
       return  $this->model->create($data);
    }

    /**
     * @param $feed_id
     * @return mixed
     */
    public function getCompositeMapping($feed_id)
    {
       return $this->model->where('fk_feed_id',$feed_id)->pluck('id','field')->toArray();
    }

    /**
     * @param $feed_id
     * @return mixed
     */
    public function removeCompositeMapping($feed_id)
    {
       return $this->model->where('fk_feed_id',$feed_id)->delete();
    }


}