<?php

namespace App\Http\Middleware;

use Closure;
use Session;
class CheckStoreSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Session::has('store_id')) {
            Session::flash('flash_info_message',trans('messages.general_notifications_lbl1'));
            return redirect()->route('store.select_store');
        }
        return $next($request);
    }
}
