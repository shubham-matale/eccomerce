<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function couponDetails()
    {
        return $this->belongsTo(CouponCode::class,'coupon_code_id','id');
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }

    public function deliveryBoy(){
        return $this->belongsTo(User::class,'delivery_boy_id','id');
    }

    public function address(){
        return $this->belongsTo(Address::class,'address_id','id');
    }

    public function allProductDetail(){
        return $this->hasManyThrough(ProductVariable::class,Order::class,'product_variable_id','order_id','id' );
    }
}
