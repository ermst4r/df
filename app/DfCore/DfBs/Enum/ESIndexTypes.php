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
 * Created by PhpStorm.
 * User: erm
 * Date: 05-05-17
 * Time: 19:16
 */

namespace App\DfCore\DfBs\Enum;


class ESIndexTypes
{
    const CHANNEL = 'channel'; // to export to a channel
    const TMP = 'tmp'; // to export to default
    const ADWORDS = 'adwords'; // to export to adwords
    const BOL = 'bol'; // to export to bol

}