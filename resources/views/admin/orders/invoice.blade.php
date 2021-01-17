<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $order->Invoice_No }}</title>
</head>


<body>
    <div class="invoice p-3 mb-3" style="background: #fff;border: 1px solid rgba(0,0,0,.125);position: relative;padding: 1rem !important;">
        <!-- title row -->
        <div class="row" style="display: -ms-flexbox;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;margin-right: -7.5px;margin-left: -7.5px;">
            <div class="col-12" style="float:left">
                <h4>
                    <small class="float-right">Date: {{$order->created_at}}</small>
                </h4>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info" style="display: -ms-flexbox;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;margin-right: -7.5px;margin-left: -7.5px;">
            <!-- /.col -->
            <div class="col-sm-4 invoice-col" style="flex: 0 0 33.333333%;max-width: 33.333333%;">
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
            <div class="col-sm-4 invoice-col" style="flex: 0 0 33.333333%;max-width: 33.333333%;">
                <b>Invoice No : {{$order->Invoice_no}}</b><br>
                <br>
                <b>Payment Status : </b> <span class="{{$order->payment_status!='paid'?'badge badge-danger':'badge badge-success'}}">{{$order->payment_status}}</span><br>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row" style="padding-top: 10px;display: -ms-flexbox;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;margin-right: -7.5px;margin-left: -7.5px;">
            <div class="col-12 table-responsive">
                <table class="table table-striped" style="border: 1px solid black;border-collapse: collapse;">
                    <thead style="border: 1px solid black">
                    <tr style="border: 1px solid black">
                        <th style="border: 1px solid black">Product Name</th>
                        <th style="border: 1px solid black">Variable Type</th>
                        <th style="border: 1px solid black">Qty</th>
                        <th style="border: 1px solid black">Prodcut Selling Price</th>
                        <th style="border: 1px solid black">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody  style="border: 1px solid black">
                    @foreach($order->orderDetails as $key=>$orderDetail)
                        <tr  style="border: 1px solid black">

                            <td  style="border: 1px solid black">{{$orderDetail->productVariable->product->name}}</td>
                            <td  style="border: 1px solid black">{{$orderDetail->productVariable->product_variable_options_name}}</td>
                            <td  style="border: 1px solid black">{{$orderDetail->quantity}}</td>
                            <td  style="border: 1px solid black">Rs. {{$orderDetail->variable_selling_price}} </td>
                            <td  style="border: 1px solid black">Rs. {{$orderDetail->quantity*$orderDetail->variable_selling_price}} </td>
                            @if($orderDetail->mirchiType!="none")
                                <table class="table table-striped ml-5" style="border: 1px solid black;border-collapse: collapse;">
                                    <thead style="border: 1px solid black">
                                    <tr style="border: 1px solid black">
                                        <th style="border: 1px solid black">Ingradient Name </th>
                                        <th style="border: 1px solid black">Ingradient Qty</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($orderDetail->customProductDetails as $key=>$customDetails)
                                        <tr style="border: 1px solid black">
                                            <td style="border: 1px solid black">{{$customDetails->ingradient->name }}</td>
                                            <td style="border: 1px solid black">{{$customDetails->required_qty}}</td>

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

        <div class="row" style="padding-top: 10px;display: -ms-flexbox;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;margin-right: -7.5px;margin-left: -7.5px;">
            <!-- accepted payments column -->
            <div class="col-6" style="flex: 0 0 15.333333%;max-width: 33.333333%;">


            </div>
            <!-- /.col -->
            <div class="col-6" style="flex: 0 0 33.333333%;max-width: 33.333333%;">
                <div class="table-responsive">
                    <table class="table" style="border: 1px solid black;border-collapse: collapse;">
                        <tbody style="border: 1px solid black">
                        @if($order->discount>0)
                            <tr style="border: 1px solid black">
                                <th style="border: 1px solid black">Discount:({{$order->couponDetails->code}})</th>
                                <td style="border: 1px solid black">Rs. {{$order->discount}}</td>
                            </tr>
                        @endif
                        <tr style="border: 1px solid black">
                            <th style="width:50% ;border: 1px solid black">Subtotal:</th>
                            <td style="border: 1px solid black">Rs. {{$order->sub_total}}</td>
                        </tr>
                        <tr style="border: 1px solid black">
                            <th style="border: 1px solid black">Tax (5%)</th>
                            <td style="border: 1px solid black">Rs. {{$order->tax}}</td>
                        </tr>
                        <tr style="border: 1px solid black">
                            <th style="border: 1px solid black">Shipping Cost:</th>
                            <td style="border: 1px solid black">Rs. {{$order->delivery_charge}}</td>
                        </tr>
                        <tr style="border: 1px solid black">
                            <th style="border: 1px solid black">Total:</th>
                            <td style="border: 1px solid black">Rs. {{$order->total_amount}}</td>
                        </tr>
                        </tbody></table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <div style="padding-top: 10px;display: -ms-flexbox;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;margin-right: -7.5px;margin-left: -7.5px; text-align: center">
            <h4>Thanks For Ordering From Shree Kakaji Masale.</h4>
        </div>
        <!-- /.row -->


    </div>
</body>
</html>

