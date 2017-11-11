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

use App\DfCore\DfBs\Rules\RuleCronjobFacade;
use App\ElasticSearch\ESCategorizeFilter;
use App\ElasticSearch\ESRules;
use App\Entity\Repository\RuleConditionRepository;
use App\Entity\Repository\RuleRepository;
use App\Entity\Repository\TmpRuleRepository;
use App\Entity\Rule;
use App\Entity\RuleCondition;
use App\Entity\TmpRule;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class InsertTmpRule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $rule_id;
    private $url_key;
    protected $component_identifier;


    /**
     * InsertTmpRule constructor.
     * @param $rule_id
     * @param $component_identifier
     * @param $url_key
     */
    public function __construct($rule_id,$component_identifier,$url_key)
    {
        $this->rule_id = (int)$rule_id;
        $this->url_key =  $url_key;
        $this->component_identifier = (int) $component_identifier;

    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {

        $rules = new RuleRepository( new Rule());
        $get_rule = $rules->getRule($this->rule_id);
        $feed_id = $get_rule->fk_feed_id;
        RuleCronjobFacade::insertTmpRules($get_rule,$this->url_key,$this->component_identifier,$get_rule->id);
        event(new \App\Events\RuleFilterProcessed($feed_id));

    }



}
