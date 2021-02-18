<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class AppExplanation extends Model
{
    use Notifiable;
    use SoftDeletes;


    protected $table = 'app_explanations';

    protected $fillable = [
        'en_title','ar_title','en_body','ar_body','image','type'
    ];

    public function setImageAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('app_explanations/'),$img_name);
        $this->attributes['image'] = $img_name ;
    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('app_explanations/'.$value);
        }else{
            return asset('default.png');
        }
    }





}
