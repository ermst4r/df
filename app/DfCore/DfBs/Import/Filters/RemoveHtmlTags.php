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

namespace App\DfCore\DfBs\Import\Filters;
use App\DfCore\DfBs\Import\Filters\Contract\iFilter;

/**
 * A simple filter to remove html tags.
 * This filter is not applied by default
 * Class RemoveHtmlTags
 * @package App\DfCore\DfBs\Import\Filters
 */
class RemoveHtmlTags implements iFilter
{
    public function handle($import_data,$mapped_fields_from_user)
    {
        foreach($import_data as $key=>$values) {
            $import_data[$key] = strip_tags($import_data[$key]);
        }

        return $import_data;
    }

}