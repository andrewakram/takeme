<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Location extends Model
{
    use Notifiable;


    protected $table = 'locations';
    protected $hidden = ['deleted_at','created_at','updated_at','user_id'];





}
