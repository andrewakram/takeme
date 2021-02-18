<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    use Notifiable;
    use SoftDeletes;


    protected $table = 'products';

    protected $fillable = [
        'shop_id','menu_id','has_sizes','price_before','price_after','name',
        'description', 'image'
    ];

    protected $hidden = [
        'active', 'deleted_at','created_at', 'updated_at','shop_id','menu_id','has_sizes'
    ];

    public function menue()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function variations()
    {
        return $this->hasMany(Variation::class, 'product_id');
    }

    public function setImageAttribute($value)
    {
        if($value) {
            $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
            $value->move(public_path('/uploads/products/'),$img_name);
            $this->attributes['image'] = $img_name ;
        }

    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/products/'.$value);
        }else{
            return asset('/default.png');
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

    public function getPercentAttribute($value)
    {
        if($value)
        {
            return (string)$value;
        }else{
            return "";
        }
    }

    public function getRateAttribute($value)
    {
        if($value)
        {
            return (string)$value;
        }else{
            return "";
        }
    }

}
