<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomProductVariable extends Model
{
    use SoftDeletes;

    public function productVariable()
    {
        return $this->belongsTo(ProductVariable::class,'product_variable_id','id');
    }

    public function ingradient()
    {
        return $this->belongsTo(MasalaIngradients::class,'ingradient_id','id');
    }

    public function customOrderIngradient(){
        return $this->belongsTo(OrderDetails::class,'ingradient_id','id');
    }
}
