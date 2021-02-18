<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Shop extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;


    protected $table = 'shops';

    protected $fillable = [
        'parent_id','department_id','name','email','phone','cover_image','category_id','country_id',
        'jwt','image','rate','lat','lng','address','city_name','password', 'image','description'
    ];

    protected $hidden = [
        'active', 'deleted_at','created_at', 'updated_at'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function rates()
    {
        return $this->hasMany(ShopRate::class, 'shop_id');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class, 'shop_id');
    }

    public function days()
    {
        return $this->hasMany(ShopDay::class,"shop_id");
    }

    public function setPasswordAttribute($value)
    {
        if($value) {
            $this->attributes['password'] = Hash::make($value);
        }else{
            $this->attributes['password'] = Hash::make('123456');
        }

    }

    public function setImageAttribute($value)
    {
        if($value) {
            $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
            $value->move(public_path('/uploads/shops/images/'),$img_name);
            $this->attributes['image'] = $img_name ;
        }

    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/shops/images/'.$value);
        }else{
            return asset('/default.png');
        }
    }

    public function getRateAttribute($value)
    {
        if($value)
        {
            return $value;
        }else{
            return "";
        }
    }

    public function setCoverImageAttribute($value)
    {
        if($value) {
            $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
            $value->move(public_path('/uploads/shops/coverimages/'),$img_name);
            $this->attributes['cover_image'] = $img_name ;
        }

    }

    public function getCoverImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/shops/coverimages/'.$value);
        }else{
            return asset('/default.png');
        }
    }

    public function getDescriptionAttribute($value)
    {
        if($value)
        {
            return $value;
        }else{
            return "";
        }
    }

    public function getQuantityAttribute($value)
    {
        if($value)
        {
            return (int)$value;
        }else{
            return 0;
        }
    }

    public static function filterbylatlng($mylat,$mylng,$radius,$model,$delegate_id=null,$user_country_id=2)
    {
        $haversine = "(6371 * acos(cos(radians($mylat))
                           * cos(radians($model.lat))
                           * cos(radians($model.lng)
                           - radians($mylng))
                           + sin(radians($mylat))
                           * sin(radians($model.lat))))";
        $datainradiusrange = Shop::select('id','name','lat','lng','address','image','cover_image','description','rate')
            ->selectRaw("{$haversine} AS distance")
            ->orderBy('distance','asc')
            ->whereRaw("{$haversine} < ?", [$radius])
            ->where('active', 1)
            ->where('country_id', $user_country_id)
            //->where('verified', 1)
            ->where('suspend', 0)
            ->orderBy('distance')
//            ->with('delegate_count')
            ->get();
        foreach ($datainradiusrange as $a){
            $a->distance = number_format($a->distance, 2, '.', '') . " "  . "كم";
            $a->delegate_count = $a->delegate_count();
            $a->orders_count = $a->orders_count();
            if(isset($delegate_id)){
                $is_subscribed = ShopDelegate::where('delegate_id',$delegate_id)
                    ->where('shop_id',$a->id)
                    ->first();
                $a->is_delegate_subscribe = isset($is_subscribed) ? 1 : 0;
            }
        }
        return $datainradiusrange;
    }

    public static function filterbylatlngbySearchKey($mylat,$mylng,$radius,$model,$delegate_id=null,$searchKey=null,$user_country_id=2)
    {
        $haversine = "(6371 * acos(cos(radians($mylat))
                           * cos(radians($model.lat))
                           * cos(radians($model.lng)
                           - radians($mylng))
                           + sin(radians($mylat))
                           * sin(radians($model.lat))))";
        $datainradiusrange = Shop::select('id','name','lat','lng','address','image','cover_image','description','rate')
            ->selectRaw("{$haversine} AS distance")
            ->orderBy('distance','asc')
            ->whereRaw("{$haversine} < ?", [$radius])
            ->where('active', 1)
            ->where('country_id', $user_country_id)
            ->where('name', 'like', '%'.$searchKey.'%')
            ->orWhere('description', 'like', '%'.$searchKey.'%')
            //->where('verified', 1)
            ->where('suspend', 0)
            ->orderBy('distance')
//            ->with('delegate_count')
            ->get();
        foreach ($datainradiusrange as $a){
            $a->distance = number_format($a->distance, 2, '.', '') . " "  . "كم";
            $a->delegate_count = $a->delegate_count();
            $a->orders_count = $a->orders_count();
            if(isset($delegate_id)){
                $is_subscribed = ShopDelegate::where('delegate_id',$delegate_id)
                    ->where('shop_id',$a->id)
                    ->first();
                $a->is_delegate_subscribe = isset($is_subscribed) ? 1 : 0;
            }
        }
        return $datainradiusrange;
    }

    public function category()
    {
        return $this->belongsTo(Category::class,"category_id");
    }

    public function delegate_count()
    {
        return $this->hasMany(ShopDelegate::class,"shop_id")
            ->count();
    }

    public function orders_count()
    {
        return $this->hasMany(Order::class,"shop_id")
            ->where('delegate_id',NULL)
            ->count();
    }

    public function waiting_orders_count()
    {
        $shop_id = request()->shop_id;
        $orders = Order::where('shop_id',$shop_id)
            ->where('delegate_id',NULL)
            ->pluck('id');
        return sizeof($orders);
//        $Waiting_order = OrderStatus::whereIn('delegate_id',$orders)
//        return $this->hasMany(Order::class,"shop_id")
//            ->where('delegate_id',NULL)
//            ->whereIn('delegate_id',$orders)
//            ->count();
    }

    public static function filterbylatlngByCatId($mylat,$mylng,$radius,$model,$cat_id,$user_country_id=2)
    {
        $haversine = "(6371 * acos(cos(radians($mylat))
                           * cos(radians($model.lat))
                           * cos(radians($model.lng)
                           - radians($mylng))
                           + sin(radians($mylat))
                           * sin(radians($model.lat))))";
        $datainradiusrange = Shop::select('id','name','lat','lng','address','image','cover_image','description')
            ->selectRaw("{$haversine} AS distance")
            ->orderBy('distance','asc')
            ->whereRaw("{$haversine} < ?", [$radius])
            ->where('category_id',$cat_id)
            ->where('country_id', $user_country_id)
            ->where('active', 1)
            //->where('verified', 1)
            ->where('suspend', 0)
            ->orderBy('distance')
            ->get();
        foreach ($datainradiusrange as $a){
            $a->distance = number_format($a->distance, 2, '.', '') . " " . "كم";
        }
        return $datainradiusrange;
    }

    public static function filterbylatlngbySearchKeyForUser($mylat,$mylng,$radius,$model,$delegate_id=null,$searchKey=null,$user_country_id=2)
    {
        $haversine = "(6371 * acos(cos(radians($mylat))
                           * cos(radians($model.lat))
                           * cos(radians($model.lng)
                           - radians($mylng))
                           + sin(radians($mylat))
                           * sin(radians($model.lat))))";
        $datainradiusrange = Shop::orderBy('distance')
            ->select('id','name','lat','lng','address','image','cover_image','description','rate','country_id')
            ->selectRaw("{$haversine} AS distance")
            ->orderBy('distance','asc')
            ->whereRaw("{$haversine} < ?", [$radius])
            ->where('active', 1)
            ->where('country_id', $user_country_id)

            ->where('name', 'like', '%'.$searchKey.'%')
            //->where('verified', 1)
            ->where('suspend', 0)


//            ->with('delegate_count')
            ->get();

        foreach ($datainradiusrange as $a){
            $a->distance = number_format($a->distance, 2, '.', '') . " "  . "كم";
            $a->delegate_count = $a->delegate_count();
            $a->orders_count = $a->orders_count();
            if(isset($delegate_id)){
                $is_subscribed = ShopDelegate::where('delegate_id',$delegate_id)
                    ->where('shop_id',$a->id)
                    ->first();
                $a->is_delegate_subscribe = isset($is_subscribed) ? 1 : 0;
            }
        }
        return $datainradiusrange;
    }



}
