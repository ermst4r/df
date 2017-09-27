<?php

namespace App\DfCore\DfBs\Log;


use App\DfCore\DfBs\Enum\LogStates;

class LoggerFacade extends DfbuilderLogger
{



    public static function addAlert($msg,$type = LogStates::ERROR)
    {
        $dfbuilderlogger =new DfbuilderLogger();
        switch($type) {
            case LogStates::ERROR:
                $dfbuilderlogger->getLogger()->addAlert($msg);
            break;

            case LogStates::CRITICAL:
                $dfbuilderlogger->getLogger()->addCritical($msg);

            break;

            case LogStates::DEBUG:
                $dfbuilderlogger->getLogger()->addDebug($msg);
            break;


        }

    }
}