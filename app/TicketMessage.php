<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketMessage extends Model
{
    use SoftDeletes;

    public function ticket(){
        return $this->belongsTo(Ticket::class,'ticket_id','id');
    }

    public function supportAgent(){
        return $this->belongsTo(User::class,'admin_id','id');
    }
}
