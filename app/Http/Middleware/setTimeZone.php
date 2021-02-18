<?php

namespace App\Http\Middleware;

use App\Models\Country;
use App\Models\Delegate;
use App\Models\Driver;
use Closure;

class setTimeZone
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
        $users = User::where($request->header('jwt'))->first();
        $drivers = Driver::where($request->header('jwt'))->first();
        $delegates = Delegate::where($request->header('jwt'))->first();
        if($users){
            $country = Country::whereId($users->user_country_id)->first();
        }
        if($drivers){
            $country = Country::whereId($drivers->country_id)->first();
        }
        if($delegates){
            $country = Country::whereId($delegates->country_id)->first();
        }
        date_default_timezone_set("$country->time_zone");
        
        return $next($request);
    }
}
