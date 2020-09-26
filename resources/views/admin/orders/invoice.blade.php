<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $order->Invoice_No }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="{{ asset('css/adminltev3.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />

    <style type="text/css" media="screen">
        * {
            font-family: "DejaVu Sans";
        }
        html {
            margin: 0;
        }
        body {
            font-size: 10px;
            margin: 36pt;
        }
        body, h1, h2, h3, h4, h5, h6, table, th, tr, td, p, div {
            line-height: 1.1;
        }
        .party-header {
            font-size: 1.5rem;
            font-weight: 400;
        }
        .total-amount {
            font-size: 12px;
            font-weight: 700;
        }
    </style>
</head>

<body>
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


    </div>
</body>
</html>

