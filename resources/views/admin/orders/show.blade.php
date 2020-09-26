@extends('layouts.admin')
@section('content')
    <div class="invoice p-3 mb-3">
    <!-- title row -->
    <div class="row">
        <div class="col-12">
            <h4>

                <small class="float-right">Date: {{$order->created_at}}</small>
            </h4>
        </div>
        <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            Customer Details
            <address>
                <strong>{{$order->customer->name}}</strong><br>
                {{$order->address->address_line_1}}<br>
                {{$order->address->address_line_2}}<br>
                Phone: {{$order->customer->mobile_no}}<br>
                Email: {{$order->customer->email}}
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            <b>Invoice No : {{$order->Invoice_no}}</b><br>
            <br>
            <b>Payment Status : </b> <span class="{{$order->payment_status!='paid'?'badge badge-danger':'badge badge-success'}}">{{$order->payment_status}}</span><br>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
        <div class="col-12 table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Variable Type</th>
                    <th>Qty</th>
                    <th>Prodcut Selling Price</th>
                    <th>Subtotal</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->orderDetails as $key=>$orderDetail)
                    <tr>

                        <td>{{$orderDetail->productVariable->product->name}}</td>
                        <td>{{$orderDetail->productVariable->product_variable_options_name}}</td>
                        <td>{{$orderDetail->quantity}}</td>
                        <td>Rs. {{$orderDetail->variable_selling_price}} </td>
                        <td>Rs. {{$orderDetail->quantity*$orderDetail->variable_selling_price}} </td>
                        @if($orderDetail->mirchiType!="none")
                            <table class="table table-striped ml-5">
                                <thead>
                                <tr>
                                    <th>Ingradient Name </th>
                                    <th>Ingradient Qty</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($orderDetail->customProductDetails as $key=>$customDetails)
                                    <tr>
                                        <td>{{$customDetails->ingradient->name }}</td>
                                        <td>{{$customDetails->required_qty}}</td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </tr>
                @endforeach


                </tbody>
            </table>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <!-- accepted payments column -->
        <div class="col-6">

            @can('assign_delivery_boy')

                @if($order->delivery_boy_id && $order->deliveryBoy && $order->deliveryBoy->name)
                    <p>Current Delivery Boy : {{$order->deliveryBoy->name}}</p>
                @else
                    <p class="lead">Assign Delivery Boy: </p>
                @endif
                @if($order->order_status_id!=4)
                <form action="{{ route("admin.orders.assignDeliveryBoy",$order->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="form-group">
                        <input hidden value="$order->id" id="order_id" name="order_id">
                        <label>{{$order->delivey_boy_id?'Change':'Choose'}} Delivery Boy</label>
                        <select class="form-control" name="delivery_boy_id" id="delivery_boy_id" value="{{ old('product_category_id', isset($order->delivery_boy_id) ? $order->delivery_boy_id : '') }}">
                            @foreach($delivery_boy_list as $key=> $deliveryBoy )
                                <option {{isset($order->delivery_boy_id) && $order->delivery_boy_id==$deliveryBoy->id?'selected':''}}  value="{{$deliveryBoy->id}}">{{$deliveryBoy->name}} </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <input class="btn btn-danger" type="submit" value="Assign Delivery Boy">
                    </div>
                </form>
                @endif
            @endcan

        </div>
        <!-- /.col -->
        <div class="col-6">
{{--            <p class="lead"></p>--}}

            <div class="table-responsive">
                <table class="table">
                    <tbody>
                    @if($order->discount>0)
                        <tr>
                            <th>Discount:({{$order->couponDetails->code}})</th>
                            <td>Rs. {{$order->discount}}</td>
                        </tr>
                    @endif
                    <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td>Rs. {{$order->sub_total}}</td>
                    </tr>
                    <tr>
                        <th>Tax (5%)</th>
                        <td>Rs. {{$order->tax}}</td>
                    </tr>
                    <tr>
                        <th>Shipping Cost:</th>
                        <td>Rs. {{$order->delivery_charge}}</td>
                    </tr>


                    <tr>
                        <th>Total:</th>
                        <td>Rs. {{$order->total_amount}}</td>
                    </tr>
                    </tbody></table>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- this row will not appear when printing -->
{{--    <div class="row no-print">--}}
{{--        <div class="col-12">--}}
{{--            <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>--}}
{{--            <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit--}}
{{--                Payment--}}
{{--            </button>--}}
{{--            <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">--}}
{{--                <i class="fas fa-download"></i> Generate PDF--}}
{{--            </button>--}}
{{--        </div>--}}
{{--    </div>--}}
</div>
@endsection
