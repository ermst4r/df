<?php

namespace App\Http\Controllers\DfCore\Common;
use App\DfCore\DfBs\Log\FeedlogFacade;
use App\DfCore\DfBs\Log\LoggerFacade;
use App\Entity\Dflogger;
use App\Entity\Repository\Contract\iDflogger;
use App\Entity\Repository\Contract\iFeedLog;
use App\Entity\Repository\Contract\iTaskLog;
use App\Entity\Repository\DfloggerRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LoggingController extends Controller
{


    private $dflogger;
    private $feedlog;
    private $task_log;
    /**
     * IndexController constructor.
     * @param iStore $store
     */
    public function __construct(iDflogger $dflogger, iFeedLog $feedlog, iTaskLog $task_log )
    {

        $this->dflogger = $dflogger;
        $this->feedlog = $feedlog;
        $this->task_log = $task_log;
    }



    /**
     * @return $this
     */
    public function log_report(\Illuminate\Http\Request $request)
    {

        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        if(is_null($start_date) && is_null($end_date)) {
            $start_date = '';
            $end_date = '';
        }




        $log_message = $this->dflogger->getLogMessage($start_date,$end_date);
        return view('dfcore.logging.log_report')->with(compact('log_message','start_date','end_date'));

    }

    public function all_feed_log(\Illuminate\Http\Request $request)
    {

        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        if(is_null($start_date) && is_null($end_date)) {
            $start_date = '';
            $end_date = '';
        }

        $log_message = $this->feedlog->getFeedLogs($start_date,$end_date);
        return view('dfcore.logging.all_feed_log')->with(compact('log_message','start_date','end_date'));

    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return $this
     */
    public function completed_process(\Illuminate\Http\Request $request)
    {
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        if(is_null($start_date) && is_null($end_date)) {
            $start_date = '';
            $end_date = '';
        }
        $task_logs = $this->task_log->getTaskLogs($start_date,$end_date);

        return view('dfcore.logging.completed_process')->with(compact('task_logs','start_date','end_date'));
    }




}
