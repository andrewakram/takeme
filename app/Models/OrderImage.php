<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class OrderImage extends Model
{
    use Notifiable;
    //use SoftDeletes;


    protected $table = 'order_images';

    protected $fillable = [
        'order_id','image',
    ];

    protected $hidden = [
        'active', 'deleted_at', 'updated_at','user_id'
    ];

//    public function products()
//    {
//        return $this->hasMany(Product::class, 'menu_id');
//    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')
            ->select('id','name','image');
    }

    function getCreatedAtAttribute()
    {
        return  Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }

    public function setImageAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('/uploads/orders/'),$img_name);
        $this->attributes['image'] = $img_name ;
    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/orders/'.$value);
        }else{
            return asset('/default.png');
        }
    }




}
