<?php
namespace App\DfCore\DfBs\Adwords;
use App\DfCore\DfBs\Enum\ESIndexTypes;
use App\DfCore\DfBs\Rules\Builder\FeedOperationDirector;
use App\ElasticSearch\DynamicFeedRepository;

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


class AdwordsHelpers
{


    /**
     * Updatehash for the ads
     * @param $match_parameters
     * @return string
     */
    public static function adUpdateHash($match_parameters)
    {
        return md5($match_parameters['headline_1'].$match_parameters['headline_2'].$match_parameters['description'].$match_parameters['path_1'].$match_parameters['path_2'].$match_parameters['final_url'].$match_parameters['generated_id']);

    }




}