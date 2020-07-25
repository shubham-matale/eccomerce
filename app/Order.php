<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    public function orderDetail()
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function couponDetails()
    {
        return $this->belongsTo(CouponCode::class,'coupon_code_id','id');
    }

    public function orderStatus()
    {
        return $this->hasOne(OrderStatus::class);
    }
}
