<?php namespace App\DfCore\DfBs\Rules\Builder;


class AdwordsRuleBuilder extends AbstractRuleBuilder
{

    private $feed_id;

    /**
     * @return mixed
     */
    public function getFeedId()
    {
        return $this->feed_id;
    }

    /**
     * @param mixed $feed_id
     */
    public function setFeedId($feed_id)
    {
        $this->feed_id = $feed_id;
    }




}