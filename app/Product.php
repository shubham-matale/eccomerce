<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'product_image_url',
        'product_subcategory_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'description',
    ];

    public function productVariable()
    {
        return $this->hasMany(ProductVariable::class)->orderBy('product_variable_option_size');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }
}
