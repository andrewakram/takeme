<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Variation extends Model
{
    use Notifiable;
    use SoftDeletes;


    protected $table = 'variations';

    protected $fillable = [
        'name','type','required','product_id'
    ];

    protected $hidden = [
        'active', 'deleted_at','created_at', 'updated_at'
    ];

    public function options()
    {
        return $this->hasMany(Option::class, 'variation_id');
    }

    public function setImageAttribute($value)
    {
        if($value) {
            $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
            $value->move(public_path('/uploads/shops/'),$img_name);
            $this->attributes['image'] = $img_name ;
        }

    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/shops/'.$value);
        }else{
            return asset('/default.png');
        }
    }


}
