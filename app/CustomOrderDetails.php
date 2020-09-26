<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomOrderDetails extends Model
{
    use SoftDeletes;

    public function ingradient()
    {
        return $this->belongsTo(MasalaIngradients::class,'ingradient_id','id');
    }
}
