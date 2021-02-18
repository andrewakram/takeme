<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Issue extends Model
{
    use Notifiable;
    use SoftDeletes;


    protected $table = 'issues';

    protected $fillable = [
        'en_issue','ar_issue','is_captin'
    ];

    public function losts()
    {
        return $this->hasMany(Lost::class, 'issue_id');
    }





}
