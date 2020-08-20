<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariable extends Model
{
    use softDeletes;
    //

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'product_id',
        'variable_original_price',
        'product_variable_options_name',
        'product_variable_option_size',
        'quantity',
        'created_at',
        'updated_at',
        'deleted_at',
        'variable_selling_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(ProductVariable::class,'product_variable_id','id');
    }

    public function customVariables()
    {
        return $this->hasMany(CustomProductVariable::class,'product_variable_id','id');
    }
}
