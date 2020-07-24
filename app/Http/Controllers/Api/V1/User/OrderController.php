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



class OrderController extends Controller{

    public $tax=5;
    public $rate_per_km=1.6;

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
                    $product=ProductVariable::with('product')->where('id','=',$eachProduct['product_variable_id'])->first();
                    if($product!=null){
                        $response['sub_total']=$response['sub_total']+($eachProduct['quantity']*$product->variable_selling_price);
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
                                }elseif ($couponCode->type=='percentage'){
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
                    $product=ProductVariable::with('product')->where('id','=',$eachProduct['product_variable_id'])->first();
                    if($product!=null){
                        $response['sub_total']=$response['sub_total']+($eachProduct['quantity']*$product->variable_selling_price);
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
                                }elseif ($couponCode->type=='percentage'){
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
                    $razorOrder  = $api->order->create(array('receipt' => $order->Invoice_No, 'amount' => $response['total']*100, 'currency' => 'INR')); // Creates order
                    $orderId = $razorOrder['id']; // Get the created Order ID
//


                    return response()->json(['success' => true,
                        'msg'=>'Order Placed',
                        'data'=>['price'=>$response,'razorpay_order_id'=>$orderId],
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
}
