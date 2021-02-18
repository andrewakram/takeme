<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class OrderRequest extends Model
{
    use Notifiable;


    protected $table = 'order_requests';

    protected $fillable = [
        'order_id','delegate_id','lat','lng','distance'
    ];

    protected $hidden = [
         'deleted_at','created_at', 'updated_at','order_id','delegate_id'
    ];


    public function order()
    {
        return $this->belongsTo(Order::class,"order_id");
    }

    public function delegate()
    {
        return $this->belongsTo(Delegate::class,"delegate_id");
    }



}
