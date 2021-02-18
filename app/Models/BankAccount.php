<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class BankAccount extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $table = 'bank_accounts';

    protected $fillable = [
        'bank_name','account_no','image',
    ];

    public function setImageAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('bank_accounts/'),$img_name);
        $this->attributes['image'] = $img_name ;
    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/bank_accounts/'.$value);
        }else{
            return asset('/default.png');
        }
    }




}
