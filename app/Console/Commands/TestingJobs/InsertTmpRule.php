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

namespace App\Console\Commands\TestingJobs;


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
use Illuminate\Console\Command;
class InsertTmpRule extends Command
{



    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert_tmp_rule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update products';




    /**
     * Create a new command instance.
     *
     * @return void
     */
    private $rule_id;
    private $es_rules;
    private $component_identifier;
    public function __construct()
    {

        parent::__construct();
        $this->rule_id = (int) 61;
        $this->component_identifier = (int) 1;
        $this->url_key = (int) 2;
        $this->update =  false;

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        $rules = new RuleRepository( new Rule());
        $get_rule = $rules->getRule($this->rule_id);
        $ruleCondition = new RuleConditionRepository( new RuleCondition());
        $feed_id = $get_rule->fk_feed_id;
        $this->es_rules = new ESRules(createEsIndexName($get_rule->fk_feed_id),DFBUILDER_ES_TYPE);
        $es_cat_field_name = es_cat_field_name($this->component_identifier,$this->url_key);
        $index_name = createEsIndexName($feed_id);
        $es_feed = new ESCategorizeFilter($index_name,DFBUILDER_ES_TYPE);

        $rule_condition = $ruleCondition->getRuleCondition($get_rule->id);
        if(count($rule_condition) > 0  ) {
            $get_condition = json_decode($ruleCondition->getRuleCondition($get_rule->id)[0], true);
            $get_condition = $get_condition['rules'];

            $products = $this->es_rules->ifJsonToESQuery($get_condition, $feed_id);

            for ($i = 0; $i < count($products); $i++) {
                foreach ($products[$i] as $generated_id => $product) {
                    $prev_rule_ids = (isset($product['_source']['rule_ids']) ? $product['_source']['rule_ids'] : []) ;
                    if(!in_array($this->rule_id,$prev_rule_ids)) {
                        array_push($prev_rule_ids,$this->rule_id);
                    }
                    $es_feed->updateDocument($product['_id'],
                        ['rule_filters' => [$es_cat_field_name=>true],
                            'rule_ids'=>$prev_rule_ids
                        ]);
                }
            }
        }


    }


}
