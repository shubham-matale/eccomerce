<?php
namespace App\Http\Controllers\Api\V1\User;


use App\Http\Controllers\Controller;
use App\Customer;
use App\Address;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Product;
use App\OrderStatus;
use App\ProductVariable;
use App\CouponCode;
use Razorpay\Api\Api;
use App\OrderDetails;
use PDF;




class OrderController extends Controller{

    public $tax=5;
    public $rate_per_km=0.6;

    public function getRate(Request $request)
    {


        try {
            $validator = Validator::make($request->all(), [
                'address_id'=>'required',
                'mobile_no'=>'required',
            ]);
            if($validator->fails()){
                return response()->json(['success' => false,
                    'msg'=>$validator->errors()], 200);
            }
            else {
                $response=[
                    'amount'=>0,
                    'tax'=>0,
                    'sub_total'=>0,
                    'discount'=>0,
                    'delivery_charge'=>30,
                    'total'=>0];
                $allProductsInCart = $request->get('products');
                foreach($allProductsInCart as $key=>$eachProduct){
                    $product=ProductVariable::with('product')->where('id','=',$eachProduct['productVariableId'])->first();
                    if($product!=null){
                        $response['sub_total']=$response['sub_total']+($eachProduct['productQuantity']*$product->variable_selling_price);
                    }


                }

                if($request->has('address_id')){
                    $address = Address::find($request->address_id);
                    if($address!=null){
                        $distance = $this->getDistanceFromLatLonInKm($address->latitude,$address->longitude,19.9851074,73.743269);
                        $response['delivery_charge']=$distance+$this->rate_per_km;
                    }
                }
                if($request->has('promocode')){
                    $couponCode = CouponCode::where('code','LIKE','%'.$request->promocode.'%')->first();
                    if($couponCode!=null){
                        if(strtolower($couponCode->code)==strtolower($request->promocode)){
                            if($response['sub_total']>=$couponCode->minimum_value){
                                if($couponCode->type=='flat'){
                                    $response['sub_total']-=$couponCode->value;
                                    $response['discount']=$couponCode->value;
                                }else if($couponCode->type=='percentage'){
                                    $response['discount']= ($response['sub_total']*($couponCode->value/100));
                                    $response['sub_total']=$response['sub_total']-($response['sub_total']*($couponCode->value/100));

                                }
                            }
                        }
                    }
                }
                $response['tax']=$response['sub_total']*($this->tax/100);
                $response['amount']=$response['sub_total']+$response['tax'];
                $response['total']=$response['amount']+$response['delivery_charge']-$response['discount'];
                return response()->json(['success' => true,
                    'data'=>$response], 200);
            }

//
        }catch (Exception $e) {
            return response()->json(['success' => false,
                'data'=>$e], 200);
        }

    }


    public function placeOrder(Request $request)
    {


        try {
            $validator = Validator::make($request->all(), [
                'address_id'=>'required',
                'mobile_no'=>'required',
            ]);
            if($validator->fails()){
                return response()->json(['success' => false,
                    'msg'=>$validator->errors()], 200);
            }
            else {
                $response=[
                    'amount'=>0,
                    'tax'=>0,
                    'sub_total'=>0,
                    'discount'=>0,
                    'delivery_charge'=>30,
                    'total'=>0];
                $allProductsInCart = $request->get('products');
                foreach($allProductsInCart as $key=>$eachProduct){
                    $product=ProductVariable::with('product')->where('id','=',$eachProduct['productVariableId'])->first();
                    if($product!=null){
                        $response['sub_total']=$response['sub_total']+($eachProduct['productQuantity']*$product->variable_selling_price);
                    }


                }

                if($request->has('address_id')){
                    $address = Address::find($request->address_id);
                    if($address!=null){
                        $distance = $this->getDistanceFromLatLonInKm($address->latitude,$address->longitude,19.9851074,73.743269);
                        $response['delivery_charge']=$distance+$this->rate_per_km;
                    }
                }
                $couponCode=null;
                if($request->has('promocode')){
                    $couponCode = CouponCode::where('code','LIKE','%'.$request->promocode.'%')->first();
                    if($couponCode!=null){
                        if(strtolower($couponCode->code)==strtolower($request->promocode)){
                            if($response['sub_total']>=$couponCode->minimum_value){
                                if($couponCode->type=='flat'){
                                    $response['sub_total']-=$couponCode->value;
                                    $response['discount']=$couponCode->value;
                                }else if($couponCode->type=='percentage'){
//                                    print_r($response['sub_total']);
                                    $response['discount']= ($response['sub_total']*($couponCode->value/100));
                                    $response['sub_total']=$response['sub_total']-($response['sub_total']*($couponCode->value/100));
                                }
                            }
                        }
                    }
                }
                $response['tax']=$response['sub_total']*($this->tax/100);
                $response['amount']=$response['sub_total']+$response['tax'];
                $response['total']=$response['amount']+$response['delivery_charge']-$response['discount'];



                $order= new Order;
                $order->Invoice_No = 'Inv-'.time();
                $order->total_amount = $response['total'];
                $order->sub_total = $response['sub_total'];
                $order->discount = $response['discount'];
                $order->tax = $response['tax'];
                if($couponCode!=null){
                    $order->coupon_code_id =$couponCode->id;
                }
                $order->delivery_charge =$response['delivery_charge'];
                if($request->has('alternate_no')){
                    $order->alternate_no = $request->alternate_no;
                }else{
                    $order->alternate_no = 'none';
                }

                $customer=Customer::where('mobile_no','=',$request->mobile_no)->first();
                if ($customer==null) {
                    return response()->json(['success' => false,
                        'msg'=>'No User Found'], 200);
                } else {
                    $order->customer_id = $customer->id;
                }
                $order->address_id=$request->address_id;
                $order->order_status_id=1;
                $orderSaveStatus=$order->save();
                if($orderSaveStatus){

                    $api_key=env('RAZOR_PAY_TEST_KEY');
                    $api_secret=env('RAZOR_PAY_TEST_SECRETE');
                    $api = new Api($api_key, $api_secret);

// Orders
                    $razorOrder  = $api->order->create(array('receipt' => $order->Invoice_No, 'amount' => $response['total']*100, 'currency' => 'INR',
                        'payment_capture'=>'1')); // Creates order
                    $orderId = $razorOrder['id']; // Get the created Order ID
                    $order->payment_gatway_order_id=$orderId;
                    $order->save();
                    foreach($allProductsInCart as $key=>$eachProduct){
                        $product=ProductVariable::with('product')->where('id','=',$eachProduct['productVariableId'])->first();
                        if($product!=null){
                            $orderDetails= new OrderDetails;
                            $orderDetails->order_id=$order->id;
                            $orderDetails->product_variable_id=$eachProduct['productVariableId'];
                            $orderDetails->quantity=$eachProduct['productQuantity'];
                            $orderDetails->variable_selling_price=$product->variable_selling_price;
                            $orderDetails->save();
                        }
                    }


                    return response()->json(['success' => true,
                        'msg'=>'Order Placed',
                        'data'=>['price'=>$response,'razorpay_order_id'=>$orderId,'order_id'=>$order->id],
                        ], 200);
                }




                return response()->json(['success' => false,
                    'msg'=>'Order Could Not Be Placed'], 200);
            }

//
        }catch (Exception $e) {
            return response()->json(['success' => false,
                'data'=>$e], 200);
        }

    }

    public function getDistanceFromLatLonInKm($lat1,$lon1,$lat2,$lon2) {

        $b1 = ($lat1/180)*M_PI;
        $b2 = ($lat2/180)*M_PI;
        $l1 = ($lon1/180)*M_PI;
        $l2 = ($lon2/180)*M_PI;
        //equatorial radius
        $r = 6378.137;
        // Formel
        $e = acos( sin($b1)*sin($b2) + cos($b1)*cos($b2)*cos($l2-$l1) );
        return round(1.60934*$r*$e, 2);

//        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$lon1."&destinations=".$lat2.",".$lon2."&mode=driving&language=pl-PL&key=AIzaSyCdY1nGoHYIermLQR4L8NeQ3HUGoJTk3Qg";
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//        $response = curl_exec($ch);
//        curl_close($ch);
//        $response_a = json_decode($response, true);
//        dd($response_a);
//        $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
//        $time = $response_a['rows'][0]['elements'][0]['duration']['text'];


    }

    public function checkPaymentStatus(Request $request){
        try {
            $api_key=env('RAZOR_PAY_TEST_KEY');
            $api_secret=env('RAZOR_PAY_TEST_SECRETE');
            $api = new Api($api_key, $api_secret);
            $validator = Validator::make($request->all(), [
                'order_id' => 'required',
                'razorpay_order_id'=>'required'
            ]);
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }
            else{
                $order = Order::find($request->order_id);
                if($order->payment_gatway_order_id == $request->razorpay_order_id)
                {
                    $razorpay_order = $api->order->fetch($request->razorpay_order_id);
                    if($razorpay_order->status == 'paid'){
                        $order = Order::find($request->order_id);
                        $order->payment_status = 'paid';
                        $order->order_status_id=2;
                        $order->save();

                        $orderDetails=OrderDetails::where('order_id',$order->id)->get();
                        foreach ($orderDetails as $key=>$orderDetail){
                            $product=ProductVariable::with('product')->where('id','=',$orderDetail->product_variable_id )->first();
                            if($product!=null){
                                $product->quantity=$product->quantity-$orderDetail->quantity>0?$product->quantity-$orderDetail->quantity:0;
                                $product->save();
                            }
                        }
                        return response()->json(['success' => true, 'msg' => 'Payment Received'], 200);
                    }else{
                        return response()->json(['success' => false, 'msg' => 'Payment Failed'], 200);
                    }
                }
                else
                {
                    return response()->json(['success' => false, 'msg' => 'razorpay_order_id Not Matching with order_id'], 200);
                }
            }

        } catch (Exception $e) {
            return response()->json(['success' => false, 'data' => $e], 400);
        }
    }

    public function getAllOrders(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'mobile_no' => 'required|numeric'
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
                    $customerOrders = Order::with(['couponDetails','customer','orderDetails','orderDetails.productVariable','orderDetails.productVariable.product','orderStatus','address','deliveryBoy'])->where('customer_id','=',$customer->id)->orderBy('id','DESC')->get();

//                    foreach ($customerOrders as $key=>$order){
//                        $orderDetails=OrderDetails::with(['productVariable','productVariable.product'])->where('order_id',$order->id)->get();
//                        $order['orderDetails']=$orderDetails;
//                    }

                    if($customerOrders->isEmpty()){
                        return response()->json(['success' => false,
                            'msg'=>'No Orders Found',
                            'data'=>[]], 200);
                    }
                    return response()->json(['success' => true,
                        'data'=>$customerOrders], 200);
                }
            }
//
        }catch (Exception $e) {
            return response()->json(['success' => false,
                'data'=>$e], 200);
        }
    }

    public function getInvoice(Request $request){
        try{

            $validator = Validator::make($request->all(), [
                'order_id' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['success' => false,
                    'msg'=>$validator->errors()], 200);
            }else{
                $order = Order::where('id',$request->order_id)->with(['couponDetails','customer','orderDetails','orderStatus','address','deliveryBoy'])->first();
                $data = $order;
                $pdf = PDF::loadHTML('vendor.invoices.default',$data);
                return $pdf->stream();
            }

        }catch (Exception $e) {
            return response()->json(['success' => false,
                'data'=>$e], 200);
        }
    }
}
