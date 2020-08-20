<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomOrderDetails extends Model
{
    use SoftDeletes;
}
