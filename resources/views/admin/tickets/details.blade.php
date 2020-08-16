@extends('layouts.admin')
@section('content')
        <div class="card">
            <div class="card-header">
                Subject : {{$ticket->subject}}<br>
                @if($ticket->assigned_to!=null && $ticket->admin->name)
                <span class="badge badge-info">Assigned To :  {{$ticket->admin->name}}</span>
                @endif
                <span style="float: right" > <a class="btn  btn-primary" target="_blank" href="{{ route('admin.orders.show', $ticket->order_id) }}">
                    View Order Details
                </a></span>
                @if($ticket->assigned_to==null)
                @can('assign_ticket_to_self')
                <span class="pr-2" style="float: right" > <a class="btn  btn-primary"  href="{{ route('admin.tickets.assign', $ticket->id) }}">
                        Assign Ticket To Self
                    </a></span>
                @endcan
                @else
                    @can('assign_ticket_to_self_force')
                        <span class="pr-2" style="float: right" >
                            <a class="btn  btn-danger"  href="{{ route('admin.tickets.assign', $ticket->id) }}">
                        Assign Ticket To Self
                        </a>
                        </span>
                    @endcan
                @endif
                @if($ticket->ticket_status_id==2)
                <span class="pr-2" style="float: right" > <a class="btn  btn-danger"  href="{{ route('admin.tickets.close', $ticket->id) }}">
                        Close Ticket
                    </a></span>
                @elseif($ticket->ticket_status_id==3)
                <span class="pr-2" style="float: right" > <a class="btn  btn-danger"  href="{{ route('admin.tickets.open', $ticket->id) }}">
                        Re-Open Ticket
                    </a></span>
                @endif
            </div>

            <div class="card-body">
                <div class="col-md-12">
                    Category : {{$ticket->category}}<br>
                    Customer Name : {{$ticket->customer->name}}<br>
                    Customer Mobile No : {{$ticket->customer->mobile_no}}<br>
                </div>
            </div>
        </div>

    <div class="row">
        <div class="col-md-12">
            <!-- The time line -->
            <div class="timeline">
                <!-- timeline time label -->
                @foreach($ticketMessages as $key=>$message)
                    <div class="time-label">
                        <span class="bg-red">{{$message->created_at}}</span>
                    </div>
                    <div>
                        <i class="fa fa-envelope bg-blue"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> 12:05</span>

                            @if($message->message_by=='admin')
                                <h3 class="timeline-header"><a style="color: orange" href="#">{{$message->supportAgent->name}}</a></h3>
                            @else
                                <h3 class="timeline-header"><a href="#">{{$ticket->customer->name}}</a> </h3>
                            @endif


                            <div class="timeline-body">
                                @if($message->message!=null)
                                    {{$message->message}}
                                @endif
                                <br>
                                @if($message->photo_url!=null)
                                    <img src="{{$message->photo_url}}" width="500">
                                @endif
                            </div>
{{--                            <div class="timeline-footer">--}}
{{--                                <a class="btn btn-primary btn-sm">Read more</a>--}}
{{--                                <a class="btn btn-danger btn-sm">Delete</a>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                @endforeach

                @if($ticket->assigned_to==Auth::User()->id && $ticket->ticket_status_id==2)
                    <div>
                        <div class="timeline-item">
                            <form  class="p-2" action="{{ route("admin.tickets.addMessage") }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group {{ $errors->has('message') ? 'has-error' : '' }}">
                                    <label for="message">Message</label>
                                    <input hidden name="ticket_id" id="tickt_id" value="{{$ticket->id}}">
                                    <input type="text" id="message" name="message" class="form-control" >
                                    @if($errors->has('message'))
                                        <p class="help-block">
                                            {{ $errors->first('message') }}
                                        </p>
                                    @endif

                                </div>

                                <div>
                                    <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

            </div>
        </div>
        <!-- /.col -->
    </div>
@endsection
