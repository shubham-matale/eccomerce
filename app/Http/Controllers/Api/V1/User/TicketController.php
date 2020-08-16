<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Customer;
use App\Address;
use App\Ticket;
use App\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function addTicket(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'subject' => 'required|string',
                'category'=>'required|string',
                'message'=>'required|string',
                'order_id'=>'numeric',
                'customer_id'=>'required|numeric',
            ]);
            if($validator->fails()){
                return response()->json(['success' => false,
                    'msg'=>$validator->errors()], 200);
            }
            else {
                $ticket=new Ticket;
                if($request->has('order_id')){
                    $ticket->order_id=$request->order_id;
                }
                $ticket->customer_id=$request->customer_id;
                $ticket->subject=$request->subject;
                $ticket->category=$request->category;
                $ticket->ticket_status_id=1;
                $ticketSaveStatus=$ticket->save();
                if($ticketSaveStatus){
                    $ticketMessage = new TicketMessage();
                    $ticketMessage->ticket_id=$ticket->id;
                    $ticketMessage->message=$request->message;
                    $ticketMessage->message_by='customer';
                    $ticketMessageSaveStatus=$ticketMessage->save();
                    if($ticketMessageSaveStatus){
                        return response()->json(['success' => true,
                            'msg'=>'Ticket Stored Succefully'], 200);
                    }else{
                        return response()->json(['success' => false,
                            'msg'=>'Error in saving ticket Message'], 200);
                    }
                }else{
                    return response()->json(['success' => false,
                        'msg'=>'Error in saving ticket'], 200);
                }

            }
//
        }catch (Exception $e) {
            return response()->json(['success' => false,
                'data'=>$e], 200);
        }
    }

    public function getAllTickets(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'mobile_no' => 'required|string',
            ]);
            if($validator->fails()){
                return response()->json(['success' => false,
                    'msg'=>$validator->errors()], 200);
            }
            else {
                $customer=Customer::where('mobile_no','=',$request->mobile_no)->first();

                if ($customer==null) {
                    return response()->json(['success' => false,
                        'msg'=>'No User Found'], 200);
                } else {
                    $tickets = Ticket::with(['customer','admin','ticketStatus','message','message.supportAgent'])->where('customer_id',$customer->id)->get();
                    if($tickets->isEmpty()){
                        return response()->json(['success' => false,
                            'msg'=>'No Tickets Found',
                        ], 200);
                    }
                    return response()->json(['success' => true,
                        'data'=>$tickets], 200);
                }

            }
//
        }catch (Exception $e) {
            return response()->json(['success' => false,
                'data'=>$e], 200);
        }
    }

    public function addMessage(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'ticket_id' => 'required',
                'message' => 'nullable|string',
                'image'=>'nullable|mimes:jpeg,bmp,png,jpg|file|max:3072'
            ]);
            if($validator->fails()){
                return response()->json(['success' => false,
                    'msg'=>$validator->errors()], 200);
            }
            else {
                $ticketMessage = new TicketMessage;
                if($request->has('message')){
                    $ticketMessage->message = $request->message;
                }
                if($request->hasFile('image')){
//                    return "shubham";
                    $ticketMessage->photo_url=$this->saveImage($request->file('image'));
                }

                $ticketMessage->ticket_id=$request->ticket_id;
                $ticketMessage->message_by = 'customer';
                $ticketMessageSaveStatus = $ticketMessage->save();
                $ticket = Ticket::with(['customer', 'admin', 'ticketStatus','message','message.supportAgent'])->where('id', $request->ticket_id)->first();

                if($ticketMessageSaveStatus){
                    return response()->json(['success' => true,
                        'data'=>$ticket], 200);
                }else{
                    return response()->json(['success' => false,
                        'data'=>$ticket,'msg'=>'Error in sending message'], 200);
                }



            }
//
        }catch (Exception $e) {
            return response()->json(['success' => false,
                'data'=>$e], 200);
        }
    }

    function saveImage($image){
        $image_name = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('images/tickets');
        $image->move($destinationPath, $image_name);
        $imageURL=env('APP_URL').'/images/tickets/'.$image_name;
        return $imageURL;
    }

    function getAllTicketMessage(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'ticket_id' => 'required',
            ]);
            if($validator->fails()){
                return response()->json(['success' => false,
                    'msg'=>$validator->errors()], 200);
            }
            else {

                $ticket = Ticket::with(['customer', 'admin', 'ticketStatus','message','message.supportAgent'])->where('id', $request->ticket_id)->first();

                if($ticket->exists){
                    return response()->json(['success' => true,
                        'data'=>$ticket], 200);
                }else{
                    return response()->json(['success' => false,
                        'data'=>[],'msg'=>'No data Found'], 200);
                }



            }
//
        }catch (Exception $e) {
            return response()->json(['success' => false,
                'data'=>$e], 200);
        }
    }

    function createCustomTicket(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'subject' => 'required|string',
                'category'=>'required|string',
                'message'=>'required|string',
                'order_id'=>'numeric',
                'customer_id'=>'required|numeric',
            ]);
            if($validator->fails()){
                return response()->json(['success' => false,
                    'msg'=>$validator->errors()], 200);
            }
            else {
                $ticket=new Ticket;
                if($request->has('order_id')){
                    $ticket->order_id=$request->order_id;
                }
                $ticket->customer_id=$request->customer_id;
                $ticket->subject=$request->subject;
                $ticket->category=$request->category;
                $ticket->ticket_status_id=1;
                $ticketSaveStatus=$ticket->save();
                if($ticketSaveStatus){
                    $ticketMessage = new TicketMessage();
                    $ticketMessage->ticket_id=$ticket->id;
                    $ticketMessage->message=$request->message;
                    $ticketMessage->message_by='customer';
                    $ticketMessageSaveStatus=$ticketMessage->save();
                    if($ticketMessageSaveStatus){
                        $customer=Customer::find($request->customer_id);

                        if ($customer==null) {
                            return response()->json(['success' => false,
                                'msg'=>'No User Found'], 200);
                        } else {
                            $tickets = Ticket::with(['customer','admin','ticketStatus','message','message.supportAgent'])->where('customer_id',$customer->id)->get();
                            if($tickets->isEmpty()){
                                return response()->json(['success' => false,
                                    'msg'=>'No Tickets Found',
                                ], 200);
                            }
                            return response()->json(['success' => true,
                                'data'=>$tickets,
                                'msg'=>'Ticket added successfully'], 200);
                        }
                        return response()->json(['success' => true,
                            'msg'=>'Ticket Stored Succefully',
                            'data'=>[]], 200);
                    }else{
                        return response()->json(['success' => false,
                            'msg'=>'Error in saving ticket Message'], 200);
                    }
                }else{
                    return response()->json(['success' => false,
                        'msg'=>'Error in saving ticket'], 200);
                }

            }
//
        }catch (Exception $e) {
            return response()->json(['success' => false,
                'data'=>$e], 200);
        }
    }
}
