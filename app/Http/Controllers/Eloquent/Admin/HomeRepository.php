<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 7/15/2019
 * Time: 10:55 PM
 */

namespace App\Http\Controllers\Eloquent\Admin;


use App\Http\Controllers\Interfaces\Admin\HomeRepositoryInterface;
use App\Models\AboutUs;
use App\Models\ComplainSuggests;
use App\Models\Order;
use App\Models\TermCondition;
use App\Models\User;
use App\Models\Worker;
use Carbon\Carbon;

class HomeRepository implements HomeRepositoryInterface
{
    public function dashboard()
    {
        $orders = Order::all();
        $this_month = new Carbon('first day of this month');

        $monthly_orders = Order::where('created_at','>=', $this_month->toDateTimeString())->get();

        $users = User::count();
        $workers = Worker::count();
        $workers_company = Worker::where('provider_id',1)->count();
        $orders_count = $orders->count();
        $monthly_orders_count = $monthly_orders->count();
        $orders_total = $orders->sum('order_total');
        $orders_total_monthly = $monthly_orders->sum('order_total');

        return ['users'=>$users,'workers'=>$workers,'orders_count'=>$orders_count,'workers_company'=>$workers_company,
            'monthly_orders_count'=>$monthly_orders_count,'orders_total'=>$orders_total,'orders_total_monthly'=>$orders_total_monthly];
    }

    public function settings($type)
    {
        if($type == 'about_us')
            return AboutUs::first();
        elseif($type == 'term_condition')
            return TermCondition::first();
    }

    public function updateSettings($type,$input)
    {
        if($type == 'about_us')
        {
            AboutUs::whereId(1)->update([
                'en_name' => $input->en_name,
                'ar_name' => $input->ar_name
            ]);
        }elseif($type == 'term_condition')
        {
            TermCondition::whereId(1)->update([
                'en_name' => $input->en_name,
                'ar_name' => $input->ar_name
            ]);
        }
    }

    public function complainSuggest()
    {
        return ComplainSuggests::all();
    }

    public function deleteComplainSuggest($id)
    {
        ComplainSuggests::whereId($id)->delete();
    }
}
