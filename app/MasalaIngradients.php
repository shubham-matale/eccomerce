<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasalaIngradients extends Model
{
    use SoftDeletes;

    public function customVariables()
    {
        return $this->hasMany(CustomProductVariable::class,'ingradient_id','id');
    }

    public function customVariablesOrderDetail()
    {
        return $this->hasMany(CustomOrderDetails::class,'ingradient_id','id');
    }
}
