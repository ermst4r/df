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



namespace App\DfCore\DfBs\Log;


use App\DfCore\DfBs\Enum\LogStates;
use App\Entity\FeedLog;
use App\Entity\Repository\FeedLogRepository;

class FeedlogFacade
{

    public static function addAlert($feed_id,$msg,$type = LogStates::ERROR)
    {
        $feedlogger =new FeedLogRepository(new FeedLog());
        $feedlogger->createFeedLog(['log_message'=>$msg,'log_type'=>$type,'fk_feed_id'=>$feed_id]);

    }
}