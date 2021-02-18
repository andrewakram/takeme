<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Setting extends Model
{
    use Notifiable;
    //use SoftDeletes;


    protected $table = 'settings';

    protected $fillable = [
        'country_id','type','money','points'
    ];

    protected $hidden = [
        'active', 'deleted_at', 'updated_at','user_id'
    ];

//    protected $appends = ['order_status','order_images'];

//    public function products()
//    {
//        return $this->hasMany(Product::class, 'menu_id');
//    }


}
