<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    public function orders(){
        return $this->hasMany(Orders::class,'address_id','id');
    }
}
