<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use SoftDeletes;

    public function productSubCategory()
    {
        return $this->hasMany(ProductSubCategory::class);
    }
}

