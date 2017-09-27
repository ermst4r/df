<?php

namespace App\Providers;
use App\Entity\Dflogger;
use App\Entity\FeedLog;
use App\Entity\Repository\DfloggerRepository;
use App\Entity\Repository\FeedLogRepository;
use App\Entity\Repository\StoreRepository;
use App\Entity\Repository\TasklogRepository;
use App\Entity\Store;
use App\Entity\Tasklog;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Session;
use Route;
class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //header
        $this->notificationProperties();
        $this->footerProperties();
        $this->layoutProperties();
        $this->menuProperties();


    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     *
     */
    public function footerProperties()
    {
        view()->composer('layouts.general.footer', function ($view) {

            #$view->with(compact('pwdactivation'));

        });
    }

    /**
     *
     */
    public function menuProperties()
    {
        view()->composer('layouts.general.menu', function ($view) {
            $action = app('request')->route()->getAction();
            $controller = class_basename($action['controller']);
            $has_store_id = Session::has('store_id');
            $view->with(compact('has_store_id','controller','action'));

        });
    }

    /**
     *
     */
    public function notificationProperties()
    {
        view()->composer('layouts.general.notifications', function ($view) {
            $store = new StoreRepository( new Store() );
            $feedlog = new FeedLogRepository( new FeedLog() );
            $dflog = new DfloggerRepository( new Dflogger() );
            $task = new TasklogRepository( new Tasklog() );
            $task_messages = $task->getTaskLogs('','',10);
            $feedlog_errors = $feedlog->getFeedLogs('','',10);
            $dflog_errors = $dflog->getLogMessage();
            $errors =  count($feedlog_errors) + count($dflog->getLogMessage());

            $stores = $store->getAllStores();
            $action = app('request')->route()->getAction();
            $controller = class_basename($action['controller']);
            $user = Auth::user();
            $current_store = null;
            if(Session::has('store_id')) {
                $current_store = $store->getStore(Session::get('store_id'));

            }


            $view->with(compact('controller', 'action','user','stores','current_store','errors','feedlog_errors','dflog_errors','task_messages'));

        });
    }

    /**
     *
     */
    public function layoutProperties()
    {
        view()->composer('layouts.frontend.layout', function ($view) {
            $user = Auth::user();
            $view->with(compact('user'));

        });
    }


}
