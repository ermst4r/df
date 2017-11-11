<?php
/**
 *  This file is part of Dfbuilder.
 *
 *     Dfbuilder is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     Dfbuilder is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with Dfbuilder.  If not, see <http://www.gnu.org/licenses/>
 */

namespace App\Jobs;

use App\DfCore\DfBs\Enum\UrlKey;
use App\ElasticSearch\ESCategorizeFilter;
use App\ElasticSearch\ESRules;
use App\Entity\Repository\RuleAdwordsRepository;
use App\Entity\Repository\RuleConditionRepository;
use App\Entity\Repository\RuleRepository;
use App\Entity\Repository\RulesChannelRepository;
use App\Entity\Repository\TmpRuleRepository;
use App\Entity\Rule;
use App\Entity\RuleAdwords;
use App\Entity\RuleCondition;
use App\Entity\RulesChannel;
use App\Entity\TmpRule;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RemoveTmpRule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $rule_id;

    protected $es_rules;

    protected $component_identifier;

    protected $url_key;


    protected $update;



    /**
     * RemoveTmpRule constructor.
     * @param $rule_id
     * @param $fk_channel_feed_id
     * @param $fk_channel_type_id
     */
    public function __construct($rule_id,$component_identifier,$url_key,$update=false)
    {

        $this->rule_id = (int) $rule_id;
        $this->component_identifier = (int) $component_identifier;
        $this->url_key = (int) $url_key;
        $this->update =  $update;

    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {



        $rules = new RuleRepository( new Rule());
        $rule_channel = new RulesChannelRepository(new RulesChannel());
        $rule_adwords = new RuleAdwordsRepository(new RuleAdwords());
        $get_rule = $rules->getRule($this->rule_id);
        $ruleCondition = new RuleConditionRepository( new RuleCondition());
        $feed_id = $get_rule->fk_feed_id;
        $es_rules = new ESRules(createEsIndexName($get_rule->fk_feed_id),DFBUILDER_ES_TYPE);
        $es_cat_field_name = es_cat_field_name($this->component_identifier,$this->url_key);
        $rule_condition = $ruleCondition->getRuleCondition($get_rule->id);
        if(count($rule_condition) > 0  ) {
            $get_condition = json_decode($ruleCondition->getRuleCondition($get_rule->id)[0], true);
            $get_condition = $get_condition['rules'];
            $products = $es_rules->ifJsonToESQuery($get_condition, $feed_id);


            /**
             * Check which rule we are in
             */
            switch($this->url_key) {
                case UrlKey::CHANNEL_FEED:
                    $current_ids = $rules->getRuleIdsFromChannel($this->component_identifier);
                break;

                case UrlKey::ADWORDS:
                    $current_ids = $rules->getRuleIdFromAdwords($this->component_identifier);
                break;

                case UrlKey::BOL:
                    $current_ids = $rules->getRuleIdsFromBol($this->component_identifier);
                break;
            }


            for ($i = 0; $i < count($products); $i++) {
                foreach ($products[$i] as $generated_id => $product) {

                    $rule_ids_array = $product['_source']['rule_ids'];
                    $entry_found = array_search($this->rule_id, $rule_ids_array);
                    if ($entry_found !== false) {
                        unset($rule_ids_array[$entry_found]);
                    }
                    $new_rule_id = [];
                    foreach($rule_ids_array as $catids) {
                        $new_rule_id[] = $catids;
                    }

                    if(isset($product['_source']['rule_ids'])) {
                        $product['_source']['rule_ids'] = $new_rule_id;
                    }



                    /**
                     * Search for this specific feed if there are still ids availble...
                     */
                    $is_entry_active = false;
                    foreach($current_ids as $current_id) {
                        if(in_array($current_id,$new_rule_id) !== false) {
                            $is_entry_active = true;
                            break;
                        }
                    }


                    $product['_source']['rule_filters'][$es_cat_field_name] = $is_entry_active;
                    $es_rules->updateDocument($product['_id'], $product['_source']);

                }
            }
        }

        switch($this->url_key) {
            case UrlKey::CHANNEL_FEED:
                $rule_channel->removeRuleChannel($this->rule_id);
            break;

            case UrlKey::ADWORDS:
                $rule_adwords->removeAdwordsRule($this->rule_id);
            break;
        }

        $rules->removeRule($this->rule_id);

        if(!$this->update) {
            event(new \App\Events\RuleFilterProcessed($feed_id));
        }

    }





}