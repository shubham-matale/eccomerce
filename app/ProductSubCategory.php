<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProductSubCategory extends Model
{
    use SoftDeletes;

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
