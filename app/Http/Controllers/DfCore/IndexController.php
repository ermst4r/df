<?php

namespace App\Http\Controllers\DfCore;
use App\DfCore\DfBs\Log\FeedlogFacade;
use App\DfCore\DfBs\Log\LoggerFacade;
use App\Entity\Repository\Contract\iDflogger;
use App\Entity\Repository\Contract\iFeed;
use App\Entity\Repository\Contract\iFeedLog;
use App\Entity\Repository\Contract\iStore;
use App\Entity\Repository\Contract\iTaskLog;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
class IndexController extends Controller
{


    private $store;
    private $dflogger;
    private $feed;
    private $feed_log;
    private $task_log;
    /**
     * IndexController constructor.
     * @param iStore $store
     */
    public function __construct( iStore $store , iDflogger $dflogger, iFeed $feed, iFeedLog $feed_log, iTaskLog $taskLog)
    {
        $this->store = $store;
        $this->dflogger = $dflogger;
        $this->feed = $feed;
        $this->feed_log = $feed_log;
        $this->task_log = $taskLog;
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::check()) {
            return redirect()->route('index.dashboard');
        }
        return view('auth.login');
    }


    /**
     * @return $this
     */
    public function dashboard()
    {
        $stores = $this->store->getAllStores();
        $task_logs = $this->task_log->getTaskLogs('','',10);
        $feed_by_store = $this->feed->getFeedByStore(session('store_id'),10);
        $log_message = $this->feed_log->getFeedLogs();
        $count_errors = count($this->dflogger->getLogMessage()) + count($log_message);
        return view('dfcore.index.dashboard')->with(compact('stores','count_errors','feed_by_store','log_message','task_logs'));
    }




}
