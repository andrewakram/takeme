<?php

namespace App\Models;

use App\Http\Controllers\Admin\OrderController;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use Notifiable;
    //use SoftDeletes;


    protected $table = 'orders';

    protected $fillable = [
        'counter','order_number','department_id','user_id','title','notes',
        'in_lat','in_lng','in_address','out_lat','out_lng','out_address',
        'delivery_time','promo_id','distance','confirm_code','delegate_id',
        'in_city_name','out_city_name','counter','offer_id','shop_id','confirm_accept',
        'country_id','total_cost'
    ];

    protected $hidden = [
        'active', 'deleted_at', 'updated_at','user_id'
    ];

    protected $appends = ['currency'];

    public function order_products()

    {
        return $this->hasMany(OrderProduct::class, 'order_id');
    }

    public function order_status()
    {
        return $this->hasOne(OrderStatus::class, 'order_id','id');
    }

    public function order_images()
    {
        return $this->hasMany(OrderImage::class, 'order_id');
    }

    public function finished_orders()
    {
        return $this->hasOne(OrderStatus::class, 'order_id')
            ->where('accept','!=', "")
            ->where('on_way','!=', "")
            ->where('finished','!=', "");
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')
            ->select('id','name','image','rate','token','email','phone');
    }

    public function delegate()
    {
        return $this->belongsTo(Delegate::class, 'delegate_id')
            ->select('id','f_name','l_name','image','lat','lng','email','phone');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id')
            ->select('id','name','image','lat','lng');
    }

    public function offer()
    {
        return $this->hasOne(OrderOffer::class, 'order_id');
    }

    public function order_offer()
    {
        return $this->hasMany(OrderOffer::class, 'order_id')
            ->select('offer','distance');
    }

    function getCreatedAtAttribute()
    {
        return  Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }

    public static function filterbylatlng($mylat,$mylng,$radius,$model,$delegate_id=null,$near_orders=null,$order_status=null,$country_id)
    {
        $subscribed_shops=null;
        if($near_orders != null && $near_orders == 1){
            $subscribed_shops = ShopDelegate::where('delegate_id',$delegate_id)->pluck('shop_id');
        }

        //$order_status==>> 0=privious , 1=current
        if(isset(request()->shop_id) && request()->shop_id !=null ){
            $haversine = "(6371 * acos(cos(radians($mylat))
                           * cos(radians($model.in_lat))
                           * cos(radians($model.in_lng)
                           - radians($mylng))
                           + sin(radians($mylat))
                           * sin(radians($model.in_lat))))";
            $datainradiusrange = Order::orderBy('id','desc')
                ->where('shop_id',request()->shop_id)
                ->where('delegate_id',$delegate_id)
//                ->where('country_id',$country_id)
                ->select('id','order_number','total_cost','title','notes','delivery_time','user_id','country_id')
                ->selectRaw("{$haversine} AS distance")
                ->whereRaw("{$haversine} < ?", [$radius])
                ->with(["order_status" => function ($query) use($order_status) {
                    if($order_status == 0){
                        $query->where('finished','!=',NULL);
                    } elseif($order_status == 1){
                        $query->where('on_way','!=',NULL);
                    }

                    }])
                ->with('order_status')
                ->with('user')
                ->with('offer')
                ->get();
        }
        if(isset(request()->department_id) && request()->department_id !=null ){
            $haversine = "(6371 * acos(cos(radians($model.in_lat))
                           * cos(radians($model.in_lat))
                           * cos(radians($model.in_lng)
                           - radians($model.in_lng))
                           + sin(radians($model.in_lat))
                           * sin(radians($model.in_lat))))";
            if(!empty($subscribed_shops)){
                $datainradiusrange = Order::orderBy('id','desc')
                    ->where('department_id',request()->department_id)
                    ->where('delegate_id',$delegate_id)
                    ->whereIn('shop_id',$subscribed_shops)
                    ->select('id','order_number','total_cost','title','notes','delivery_time','user_id','shop_id','country_id')
                    ->selectRaw("{$haversine} AS distance")
                    ->whereRaw("{$haversine} < ?", [$radius])
                    ->with(["order_status" => function ($query) use($order_status) {
//                        if($order_status == 0){
//                            $query->where('finished','!=',NULL);
//                        } elseif($order_status == 1){
//                            $query->where('accept',NULL);
//                        }

                    }])
                    ->with('user')
                    ->with('offer')
//                    ->with('order_status')
                    ->get();
            }else{
                $datainradiusrange = Order::orderBy('id','desc')
                    ->where('department_id',request()->department_id)
                    ->where('delegate_id',$delegate_id)
                    ->select('id','order_number','total_cost','title','notes','delivery_time','user_id','shop_id','country_id')
                    ->selectRaw("{$haversine} AS distance")
                    ->whereRaw("{$haversine} < ?", [$radius])
                    ->with(["order_status" => function ($query) use($order_status) {
//                        if($order_status == 0){
//                            $query->where('finished','!=',NULL);
//                        } elseif($order_status == 1){
//                            $query->where('accept',NULL);
//                        }

                    }])
                    ->with('user')
                    ->with('offer')
                    ->with('order_status')
                    ->get();
            }


        }

        return $datainradiusrange;
    }

    public static function filterDelegates($mylat,$mylng,$radius,$model,$delegate_id=null,$near_orders=null,$order_status=null,$user_country_id=2)
    {
        $haversine = "(6371 * acos(cos(radians($mylat))
                           * cos(radians($model.lat))
                           * cos(radians($model.lng)
                           - radians($mylng))
                           + sin(radians($mylat))
                           * sin(radians($model.lat))))";

//        $haversine = "(6371 * acos(cos(radians($model.lat))
//                           * cos(radians($model.lat))
//                           * cos(radians($model.lng)
//                           - radians($model.lng))
//                           + sin(radians($model.lat))
//                           * sin(radians($model.lat))))";
        $datainradiusrange = Delegate::orderBy('id','desc')
            ->where('online',1)
            ->where('country_id',$user_country_id)
//            ->where('department_id',request()->department_id)
//            ->where('delegate_id',$delegate_id)
//            ->whereIn('shop_id',$subscribed_shops)
            ->select('id','lat','lng','token')
            ->selectRaw("{$haversine} AS distance")
            ->whereRaw("{$haversine} < ?", [$radius])
//            ->with('order_status')
//            ->with('user')
            ->get();

        return $datainradiusrange;
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
//
//    function getCountryIdAttribute()
//    {
//        return  $this->country()->first()->name;
//    }


    function getTotalCostAttribute($value)
    {
        return  (string)$value;
    }

    function getCurrencyAttribute()
    {
        if(isset($this->attributes['country_id'])){
            return (string)Country::whereId($this->attributes['country_id'])->first()->currency;
        }
        //$country_id = $this->attributes['country_id'];
        if(!isset($this->attributes['country_id'])){
            return "درهم اماراتي";
        }
//            $country_id=2;
//

    }

    function getPromoIdAttribute($value)
    {
        return  (int)$value;
    }

    function getShopRateAttribute($value)
    {
        return  (string)$value;
    }

    function getShopCommentAttribute($value)
    {
        return  (string)$value;
    }

    function getDelegateRateAttribute($value)
    {
        return  (string)$value;
    }

    function getDelegateCommentAttribute($value)
    {
        return  (string)$value;
    }

    function getUserRateAttribute($value)
    {
        return  (string)$value;
    }

    function getUserCommentAttribute($value)
    {
        return  (string)$value;
    }

    function getOrderStatusAttribute($value)
    {
        return  (object)$value;
    }

//    function getOrderImagesAttribute($value)
//    {
//        return  (array)$value;
//    }

    public function getOrderImagesAttribute()
    {
        dd($this->getRelation('order_images'));
        if ( ! array_key_exists('order_images', $this->relations)) $this->load('order_images');

        $data = ($this->getRelation('order_images')) ? [] : [];

        return $data;
    }

    public function getShopIdAttribute($value)
    {
        if($value)
            return $value;
        else
            return 0;
    }

    public function getOfferIdAttribute($value)
    {
        if($value)
            return $value;
        else
            return 0;
    }

    public function getDelegateIdAttribute($value)
    {
        if($value)
            return $value;
        else
            return 0;
    }

    public function getDeliveryTimeAttribute($value)
    {
        if($value)
            return $value;
        else
            return "";
    }

    public function getDistanceAttribute($value)
    {
        if($value)
            return $value;
        else
            return "";
            return "";
    }

    public static function filterbylatlngWaitingOrderforDelegates($mylat,$mylng,$radius,$model,$delegate_id=null,$near_orders=null,$order_status=null,$delegate_country_id)
    {
        $date = Carbon::now()->subDays(15);
        $waitingOrders = OrderStatus::join('orders','orders.id','order_statuses.order_id')
            ->where('orders.created_at','>',$date)
            ->where('accept',NULL)
            ->where('department_id',request()->department_id)
            ->pluck('order_id');

        $haversine = "(6371 * acos(cos(radians($mylat))
                           * cos(radians($model.in_lat))
                           * cos(radians($model.in_lng)
                           - radians($mylng))
                           + sin(radians($mylat))
                           * sin(radians($model.in_lat))))";
        $datainradiusrange = Order::orderBy('id','desc')
//            ->where('country_id',$delegate_country_id)
            ->select('*')
            ->selectRaw("{$haversine} AS distance")
            ->whereRaw("{$haversine} < ?", [$radius])
            ->whereIn('id',$waitingOrders)
            ->with('shop')
            ->with('order_status')
            ->with('user')
            ->get();

        return $datainradiusrange;
    }


}
