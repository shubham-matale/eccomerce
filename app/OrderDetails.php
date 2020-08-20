<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetails extends Model
{
    use SoftDeletes;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function productVariable()
    {
        return $this->belongsTo(ProductVariable::class,'product_variable_id','id');
    }

    public function product(){
        return $this->hasManyThrough(Product::class,ProductVariable::class,'product_variable_id','product_id','id','id' );
    }

    public function customProductDetails(){
        return $this->hasMany(CustomOrderDetails::class,'order_details_id','id');
    }

}
