<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class AboutUs extends Model
{
    use Notifiable;


    protected $table = 'aboutus';

    protected $fillable = [
        'id','body_ar', 'body_en','type'
    ];





}
