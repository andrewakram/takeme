<?php

namespace App\Listeners;

use App\Events\ShopRateSavedEvent;
use App\Models\Shop;
use App\Models\ShopRate;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ShopRateSavedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ShopRateSavedEvent  $event
     * @return void
     */
    public function handle(ShopRateSavedEvent $event)
    {
        $shop_rate = $event->shop_rate;
        if($shop_rate->shop_id != 0){
            $rates = ShopRate::where('shop_id',$shop_rate->shop_id);
            $sum = $rates->sum('rate');
            $count = $rates->count();
            //
            $store = Shop::where('id',$shop_rate->shop_id)->first();
            $store->rate = number_format((float)($sum/$count), 1, '.', '');
            $store->save();
        }

    }
}
