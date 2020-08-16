<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ $data->Invoice_No }}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <link rel="stylesheet" href="{{ asset('/vendor/invoices/bootstrap.min.css') }}">

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

                    <small class="float-right">Date: {{$data->created_at}}</small>
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
                    <strong>{{$data->customer->name}}</strong><br>
                    {{$data->address->address_line_1}}<br>
                    {{$data->address->address_line_2}}<br>
                    Phone: {{$data->customer->mobile_no}}<br>
                    Email: {{$data->customer->email}}
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <b>Invoice No : {{$data->Invoice_No}}</b><br>
                <br>
                <b>Payment Status : </b> <span class="{{$data->payment_status!='paid'?'badge badge-danger':'badge badge-success'}}">{{$data->payment_status}}</span><br>
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
                    @foreach($data->orderDetails as $key=>$dataDetail)
                        <tr>

                            <td>{{$dataDetail->productVariable->product->name}}</td>
                            <td>{{$dataDetail->productVariable->product_variable_options_name}}</td>
                            <td>{{$dataDetail->quantity}}</td>
                            <td>Rs. {{$dataDetail->variable_selling_price}} </td>
                            <td>Rs. {{$dataDetail->quantity*$dataDetail->variable_selling_price}}</td>
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
                        @if($data->discount>0)
                            <tr>
                                <th>Discount:({{$data->couponDetails->code}})</th>
                                <td>Rs. {{$data->discount}}</td>
                            </tr>
                        @endif
                        <tr>
                            <th style="width:50%">Subtotal:</th>
                            <td>Rs. {{$data->sub_total}}</td>
                        </tr>
                        <tr>
                            <th>Tax (5%)</th>
                            <td>Rs. {{$data->tax}}</td>
                        </tr>
                        <tr>
                            <th>Shipping Cost:</th>
                            <td>Rs. {{$data->delivery_charge}}</td>
                        </tr>


                        <tr>
                            <th>Total:</th>
                            <td>Rs. {{$data->total_amount}}</td>
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

        <script type="text/php">
            if (isset($pdf) && $PAGE_COUNT > 1) {
                $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width);
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>
    </body>
</html>
