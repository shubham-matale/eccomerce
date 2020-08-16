<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    public function orders(){
        return $this->hasMany(Order::class,'customer_id','id');
    }

    public function customer(){
        return $this->hasMany(Ticket::class,'customer_id','id');
    }
}
