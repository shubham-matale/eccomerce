<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <style>
            * {
                font-size: 12px;
                font-family: 'Times New Roman';
            }

            td,
            th,
            tr,
            table {
                border-top: 1px solid black;
                border-collapse: collapse;
            }

            td.description,
            th.description {
                width: 75px;
                max-width: 75px;
            }

            td.quantity,
            th.quantity {
                width: 40px;
                max-width: 40px;
                word-break: break-all;
            }

            td.price,
            th.price {
                width: 40px;
                max-width: 40px;
                word-break: break-all;
            }

            .centered {
                text-align: center;
                align-content: center;
                margin: 0;
            }

            .ticket {
                width: 155px;
                max-width: 155px;
            }

            img {
                max-width: inherit;
                width: inherit;
            }

            @media print {
                .hidden-print,
                .hidden-print * {
                    display: none !important;
                }
            }
        </style>
        <title>Receipt example</title>
    </head>
    <body onload="window.print()">
        <div class="ticket">
<!--            <img src="./logo.png" alt="Logo">-->
            <h4 class="centered">Shree Kakaji Masale</h4>
            <p class="centered">
                Om Plaza Shop NO 2 Trimurti Chowk,
                Nashik, Maharashtra 422008</p>
            <table>
                <thead>
                    <tr>
                        <th class="quantity">Q.</th>
                        <th class="description">Description</th>
                        <th class="price">$$</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($order->orderDetails as $key=>$orderDetail)
                    <tr>
                        <td class="quantity">{{$orderDetail->quantity}}</td>
                        <td class="description">{{$orderDetail->productVariable->product->name}} ({{$orderDetail->productVariable->product_variable_options_name}})</td>
                        <td class="price">{{$orderDetail->quantity*$orderDetail->variable_selling_price}}</td>
                    </tr>
                @endforeach
                    <tr>
                            <th >Subtotal:</th>
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
                </tbody>
            </table>
            <p class="centered">Thanks for your purchase!
                <br>parzibyte.me/blog</p>
        </div>
        <button id="btnPrint" class="hidden-print">Print</button>
        <script>
            const $btnPrint = document.querySelector("#btnPrint");
            $btnPrint.addEventListener("click", () => {
                window.print();
            });
        </script>
    </body>
</html>
