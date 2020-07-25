<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponCode extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'code',
        'type',
        'from_date',
        'to_date',
        'value',
        'minimum_value',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function order()
    {
        return $this->hasMany(Order::class,'coupon_code_id','id');
    }
}
