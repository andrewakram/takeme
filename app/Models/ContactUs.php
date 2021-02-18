<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ContactUs extends Model
{
    use Notifiable;


    protected $table = 'contact_us';

    protected $fillable = [
        'id','name', 'email','phone','message','type'
    ];





}
