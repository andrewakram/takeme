<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PromoCode extends Model
{
    use Notifiable;


    protected $table = 'promo_codes';

    protected $hidden = ['car_level_ids', 'deleted_at', 'updated_at',];
    protected $fillable = ['code'];



}
