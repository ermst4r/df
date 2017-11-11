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


namespace App\DfCore\DfBs\Enum;


class UpdateIntervals
{

    const DAILY = 86400;
    const TWO_DAILY = 172800;
    const THREE_DAILY = 259200;
    const WEEKLY = 604800;
    const HOURHLY = 3600;
    const TWO_HOURLY = 7200;
    const SIX_HOURLY = 21600;
    const EVERY_THIRTY_MINUTES = 1800;

    public static function updateSelectBoxArray()
    {
        return
            [
                self::DAILY =>trans('messages.update_key_lbl1'),
                self::TWO_DAILY =>trans('messages.update_key_lbl2'),
                self::THREE_DAILY =>trans('messages.update_key_lbl3'),
                self::EVERY_THIRTY_MINUTES =>trans('messages.update_key_lbl7'),
                self::HOURHLY =>trans('messages.update_key_lbl4'),
                self::TWO_HOURLY =>trans('messages.update_key_lbl5'),
                self::SIX_HOURLY =>trans('messages.update_key_lbl6'),

            ];
    }


}