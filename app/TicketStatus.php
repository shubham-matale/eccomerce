<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketStatus extends Model
{
    use SoftDeletes;

    public function tickets(){
        return $this->hasMany(Ticket::class,'ticket_status_id','id');
    }
}
