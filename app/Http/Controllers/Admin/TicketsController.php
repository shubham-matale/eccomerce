<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Ticket;
use App\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class TicketsController extends Controller{

    public function index(){
        abort_unless(\Gate::allows('view_tickets'), 403);
        $tickets = Ticket::with(['customer','admin','ticketStatus'])->get();
        return view('admin.tickets.index',compact(['tickets']));
    }

    public function getMessage($id){
        abort_unless(\Gate::allows('view_tickets'), 403);
        $ticket = Ticket::with(['customer','admin','ticketStatus'])->where('id',$id)->first();
        $ticketMessages=TicketMessage::with(['supportAgent'])->where('ticket_id',$id)->get();

        return view('admin.tickets.details',compact(['ticket','ticketMessages']));
    }

    public function assignToMe($id){
        abort_unless(\Gate::allows('assign_ticket_to_self'), 403);
        $ticket = Ticket::with(['customer','admin','ticketStatus'])->where('id',$id)->first();
        $ticket->assigned_to=Auth::user()->id;
        $ticket->ticket_status_id=2;
        $ticket->save();
        $ticket = Ticket::with(['customer','admin','ticketStatus'])->where('id',$id)->first();
        $ticketMessages=TicketMessage::with(['supportAgent'])->where('ticket_id',$id)->get();

        return view('admin.tickets.details',compact(['ticket','ticketMessages']));
    }

    public function close($id){
        abort_unless(\Gate::allows('handle_ticket'), 403);
        $ticket = Ticket::with(['customer','admin','ticketStatus'])->where('id',$id)->first();
        $ticket->ticket_status_id=3;
        $ticket->save();
        $ticketMessages=TicketMessage::with(['supportAgent'])->where('ticket_id',$id)->get();

        return view('admin.tickets.details',compact(['ticket','ticketMessages']));
    }

    public function open($id){
        abort_unless(\Gate::allows('handle_ticket'), 403);
        $ticket = Ticket::with(['customer','admin','ticketStatus'])->where('id',$id)->first();
        $ticket->ticket_status_id=2;
        $ticket->save();
        $ticketMessages=TicketMessage::with(['supportAgent'])->where('ticket_id',$id)->get();

        return view('admin.tickets.details',compact(['ticket','ticketMessages']));
    }
    public function addMessage(Request $request)
    {
        abort_unless(\Gate::allows('handle_ticket'), 403);
        $validator = Validator::make($request->all(), [
            'ticket_id' => 'required|string',
            'message' => 'required|string',
        ]);

        $ticketMessage = new TicketMessage;
        $ticketMessage->message = $request->message;
        $ticketMessage->ticket_id=$request->ticket_id;
        $ticketMessage->message_by = 'admin';
        $ticketMessage->admin_id=Auth::user()->id;
        $ticketMessageSaveStatus = $ticketMessage->save();
        $ticket = Ticket::with(['customer', 'admin', 'ticketStatus'])->where('id', $request->ticket_id)->first();
        $ticketMessages = TicketMessage::with(['supportAgent'])->where('ticket_id', $request->ticket_id)->get();

        return redirect()->route('admin.tickets.getMessage',$ticket->id);
//        return view('admin.tickets.details', compact(['ticket', 'ticketMessages']));
    }

}


?>
