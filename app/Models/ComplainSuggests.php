<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplainSuggests extends Model
{
    protected $table ="complains_suggestions";
    protected $fillable = [
      'user_id','title','description','issue_id','lost_id','order_id','type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function delegate()
    {
        return $this->belongsTo(Delegate::class,'user_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class,'user_id');
    }

    public function issue()
    {
        return $this->belongsTo(Issue::class,'issue_id');
    }

    public function lost()
    {
        return $this->belongsTo(Lost::class,'lost_id');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class,'trip_id');
    }

}
