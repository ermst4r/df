<?php

namespace App\Entity\Repository;


use App\Entity\CategoryFilter;
use App\Entity\Repository\Contract\iCategoryFilter;
use DB;


class CategoryFilterRepository implements iCategoryFilter
{

    private $categoryFilter;

    /**
     * CategoryFilterRepository constructor.
     * @param CategoryFilter $categoryFilter
     */
    public function __construct(CategoryFilter $categoryFilter)
    {
        $this->categoryFilter = $categoryFilter;
    }


    /**
     * Create the categoryfilter
     * @param array $data
     * @return mixed
     */
    public function createCategoryFilter($data = array(), $id=0)
    {
        if($id == 0) {
            return  $this->categoryFilter->create($data);
        } else {
            $this->categoryFilter->find($id)->update($data);
            return $id;
        }

    }


    /**
     * Get the category filter by id
     * @param $id
     * @return mixed
     */
    public function getCategoryFilter($id)
    {
        return  $this->categoryFilter->findOrFail($id);
    }

    /**
     * @param $fk_channel_feed_id
     * @return mixed
     */
    public function getChannelCategories($id,$from_feed_id = false)
    {
        $table = $this->categoryFilter->getTable();
        $where = 'category_channel.fk_channel_feed_id';
        if($from_feed_id) {
            $where = $table.'.fk_feed_id';
        }

        return DB::table($table)
            ->join('category_channel', $table.'.id', '=', 'category_channel.fk_category_filter_id')
            ->join('category', $table.'.fk_category_id', '=', 'category.id')
            ->select(DB::RAW($table.'.id AS id,'.$table.'.fk_feed_id AS fk_feed_id, 
            '.$table.'.fk_category_id AS fk_category_id , '.$table.'.field AS field,'.$table.'.condition AS category_condition, '.$table.'.visible AS visible,'.$table.'.phrase AS phrase 
            ,category.id AS category_id,category.category_name AS category_name,category.category_meta AS category_meta, category_channel.*'))
            ->where($where,$id)
            ->where($table.'.visible',true)->get();

    }


    /**
     * @param $fk_bol_id
     * @return mixed
     */
    public function getBolCategories($id,$from_feed_id = false)
    {
        $table = $this->categoryFilter->getTable();
        $where = 'category_bol.fk_bol_id';
        if($from_feed_id) {
            $where = $table.'.fk_feed_id';
        }

        return DB::table($table)
            ->join('category_bol', $table.'.id', '=', 'category_bol.fk_category_filter_id')
            ->join('category', $table.'.fk_category_id', '=', 'category.id')
            ->select(DB::RAW($table.'.id AS id,'.$table.'.fk_feed_id AS fk_feed_id, 
            '.$table.'.fk_category_id AS fk_category_id , '.$table.'.field AS field,'.$table.'.condition AS category_condition, '.$table.'.visible AS visible,'.$table.'.phrase AS phrase 
            ,category.id AS category_id,category.category_name AS category_name,category.category_meta AS category_meta,category_bol.*'))
            ->where($where,$id)
            ->where($table.'.visible',true)->get();

    }

    /**
     * @param $id
     * @param bool $channel_feed_level
     * @return mixed
     */
    public function getCategoryFilterFromFeed($id,$channel_feed_level=false)
    {
        if(!$channel_feed_level) {
            return $this->categoryFilter->where('fk_feed_id','=',$id)->where('visible','=',true)->get();
        } else {
            return $this->categoryFilter->where('fk_channel_feed_id','=',$id)->where('visible','=',true)->get();
        }

    }

    public function deleteFilter($filter_id)
    {
        $this->categoryFilter->findOrFail($filter_id)->delete();

    }


    /**
     * @param $fk_channel_feed_id
     * @param $fk_channel_type_id
     * @return mixed
     */
    public function getCatIdsFromChannel($fk_channel_feed_id,$visible = null)
    {
        $table = $this->categoryFilter->getTable();
        if(is_null($visible)) {
            return DB::table($table)
                ->join('category_channel', $table.'.id', '=', 'category_channel.fk_category_filter_id')
                ->select(DB::RAW($table.'.id AS id,'.$table.'.fk_feed_id AS fk_feed_id, 
            '.$table.'.fk_category_id AS fk_category_id , '.$table.'.field AS field,'.$table.'.condition AS category_condition, '.$table.'.visible AS visible,'.$table.'.phrase AS phrase'))
                ->where('category_channel.fk_channel_feed_id',$fk_channel_feed_id)
                ->pluck('id')->toArray();
        } else {
            return DB::table($table)
                ->join('category_channel', $table.'.id', '=', 'category_channel.fk_category_filter_id')
                ->select(DB::RAW($table.'.id AS id,'.$table.'.fk_feed_id AS fk_feed_id, 
            '.$table.'.fk_category_id AS fk_category_id , '.$table.'.field AS field,'.$table.'.condition AS category_condition, '.$table.'.visible AS visible,'.$table.'.phrase AS phrase'))
                ->where('category_channel.fk_channel_feed_id',$fk_channel_feed_id)
                ->where($table.'.visible',$visible)
                ->pluck('id')->toArray();
        }
    }








    /**
     * @param $fk_channel_feed_id
     * @param $fk_channel_type_id
     * @return mixed
     */
    public function getCatIdsFromBol($bol_id)
    {
        $table = $this->categoryFilter->getTable();
        return DB::table($table)
            ->join('category_bol', $table.'.id', '=', 'category_bol.fk_category_filter_id')
            ->select(DB::RAW($table.'.id AS id,'.$table.'.fk_feed_id AS fk_feed_id, 
            '.$table.'.fk_category_id AS fk_category_id , '.$table.'.field AS field,'.$table.'.condition AS category_condition, '.$table.'.visible AS visible,'.$table.'.phrase AS phrase'))
            ->where('category_bol.fk_bol_id',$bol_id)
            ->pluck('id')->toArray();

    }





}