<?php
/**
 * Created by PhpStorm.
 * User: erm
 * Date: 17-04-17
 * Time: 18:33
 */

namespace App\DfCore\DfBs\Rules\RuleStrategy;


use App\DfCore\DfBs\Enum\RuleConditions;

class RegisterStrategy
{

    /*
      |--------------------------------------------------------------------------
      | Then Rules
      |--------------------------------------------------------------------------
      |
      | Over here you can register the strategy for a rule. The rules will be automatically loaded
      | in the  processRules job.
      |
      |
      */

   public function loadRules()
   {
       return [
           RuleConditions::THEN_APPEND_VALUE => new  \App\DfCore\DfBs\Rules\RuleStrategy\Strategies\AppendStrategy,
           RuleConditions::THEN_FIND_AND_REPLACE => new  \App\DfCore\DfBs\Rules\RuleStrategy\Strategies\FindReplaceStrategy,
           RuleConditions::THEN_FIND_AND_REPLACE_FIELD_NAME => new  \App\DfCore\DfBs\Rules\RuleStrategy\Strategies\FindReplaceFieldStrategy,
           RuleConditions::THEN_ALTER_FIELD_VALUE => new  \App\DfCore\DfBs\Rules\RuleStrategy\Strategies\AlterFieldValueStrategy,
           RuleConditions::THEN_COPY_VALUE_FROM_FIELD => new  \App\DfCore\DfBs\Rules\RuleStrategy\Strategies\CopyValueFromFieldStrategy,
           RuleConditions::THEN_COMBINE_FEED_VALUE => new  \App\DfCore\DfBs\Rules\RuleStrategy\Strategies\CombineFieldValue,
           RuleConditions::THEN_FIND_REPLACE_OTHER_FIELD => new  \App\DfCore\DfBs\Rules\RuleStrategy\Strategies\ThenFindReplaceOtherFieldStrategy,
           RuleConditions::THEN_SPLIT_FIELD => new  \App\DfCore\DfBs\Rules\RuleStrategy\Strategies\SplitFieldStrategy,
           RuleConditions::THEN_STRING_LENGTH => new  \App\DfCore\DfBs\Rules\RuleStrategy\Strategies\StringLengthStrategy,
           RuleConditions::THEN_COMMON_STRING_ACTIONS => new  \App\DfCore\DfBs\Rules\RuleStrategy\Strategies\CommonStringActions,
           RuleConditions::THEN_GOOGLE_TRACKING => new  \App\DfCore\DfBs\Rules\RuleStrategy\Strategies\GoogleTracking,
           RuleConditions::THEN_ROUND_NUMBER => new  \App\DfCore\DfBs\Rules\RuleStrategy\Strategies\ThenRoundNumberStrategy,
           RuleConditions::THEN_CALCULATE_NUMBER => new  \App\DfCore\DfBs\Rules\RuleStrategy\Strategies\CalculateNumberStrategy,
           RuleConditions::THEN_CALCULATE_SUM => new  \App\DfCore\DfBs\Rules\RuleStrategy\Strategies\CalculateSumStrategy,
           RuleConditions::THEN_CALCULATE_STRING_LENGTH => new  \App\DfCore\DfBs\Rules\RuleStrategy\Strategies\CalculateStringLengthStrategy,
       ];
   }


}