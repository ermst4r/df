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

namespace App\DfCore\DfBs\Import\Category;
use App\DfCore\DfBs\Enum\CategoryChannels;
use App\DfCore\DfBs\Import\Category\CategoryChannels\GoogleShopping;


/**
 * Let us instantiate a specific file
 * Class CsvMapping
 * @package App\DfCore\DfBs\Import\Csv
 */
class CategoryImportFactory  {

    /**
     * @param $channel
     * @return array
     * @throws \Exception
     */
    public static function setChannel($channel)
    {
        switch ($channel) {
            case CategoryChannels::GOOGLE_SHOPPING:
                $googleShopping = new GoogleShopping();
                return $googleShopping->parseChannelData();
            break;



            default :
                throw new \Exception("No Channel has been set!");
        }
    }


}