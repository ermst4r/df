<?php
/**
 * Created by PhpStorm.
 * User: erm
 * Date: 24-02-17
 * Time: 18:52
 */

namespace App\DfCore\DfBs\Enum;


class ConditionSelector
{
    const CONTAIN = 1;
    const NOT_CONTAIN = 2;
    const EQUALS = 3;
    const NOT_EQUALS = 4;
    const IS_REGEXP = 5;
    const NOT_REGEXP = 6;
    const IS_EMPTY = 7;
    const IS_NOT_EMPTY = 8;
    const CONTAINS_MULTI = 9;
    const NOT_CONTAINS_MULTI = 10;
    const EQUALS_MULTI = 11;
    const NOT_EQUALS_MULTI = 12;
    const GT = 13;
    const GT_EQ = 14;
    const LT = 15;
    const LT_EQ = 16;
    const BY_FEED = 18;



}