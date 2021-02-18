<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Auth;

class SetLocale
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
        $lang =Session::get('lang');
        /*$lang='ar';
        dd($lang);*/
        if(isset($lang)){
            Session::put('lang',$lang);
            app()->setLocale($lang);
        }else{
            Session::put('lang','ar');
            app()->setLocale('ar');
        }
        //dd(Session::get('lang'));
        return $next($request);
    }
}
