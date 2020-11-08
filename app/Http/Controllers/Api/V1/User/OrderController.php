<?php
namespace App\Http\Controllers\Api\V1\User;


use App\CustomOrderDetails;
use App\CustomProductVariable;
use App\Http\Controllers\Controller;
use App\Customer;
use App\Address;
use App\Mail\SendMail;
use App\MasalaIngradients;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
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
    public $api_key="rzp_test_rY0vOBNRFJeB5T";
    public $api_secret="k7cLsdpjeVgOScaSy9iT21qc";

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
                    'delivery_charge'=>0,
                    'total'=>0,
                    'grinding_charge'=>0,
                    'product_total_weight'=>0];
                $allProductsInCart = $request->get('products');
//                error_log($allProductsInCart);
                foreach($allProductsInCart as $key=>$eachProduct){
                    $product=ProductVariable::with('product')->where('id','=',$eachProduct['productVariableId'])->first();
                    if($product!=null){
                        $response['sub_total']=$response['sub_total']+($eachProduct['productQuantity']*$product->variable_selling_price);
                        $response['product_total_weight']=round($response['product_total_weight']+($eachProduct['productQuantity']*$product->product_variable_option_size), 2);
                        if(array_key_exists('customiseProductTypeOfMirchi',$eachProduct)  && $eachProduct['isCustomiseProduct']==1){
                            switch ($eachProduct['customiseProductTypeOfMirchi']) {
                                case "Medium Mirchi":
                                    $response['sub_total']=$response['sub_total']+($product['medium_mirchi_price']*$eachProduct['productQuantity']);
                                    break;
                                case "Spicy Mirchi":
                                    $response['sub_total']=$response['sub_total']+($product['spicy_mirchi_price']*$eachProduct['productQuantity']);
                                    break;
                                case "Mirchi":
                                    $response['sub_total']=$response['sub_total']+($product['less_mirchi_price']*$eachProduct['productQuantity']);
                                    break;
                            }
                            $response['grinding_charge']=$response['grinding_charge']+($product['grinding_price']*$eachProduct['productQuantity']);
                            $response['sub_total']=$response['sub_total']+$response['grinding_charge'];
                        }
                    }


                }

                if($request->has('address_id')){
                    $address = Address::find($request->address_id);
                    $nashikPincodeArray=[422012,423502,422003,423201,423208,422209,422215,423301,423301,422004,422211,423202,422212,422206,422208,422010,422010,423204,422003,422001,422212,422001,422203,423402,423402,422213,423104,423202,423301,422208,422003,423303,423205,422212,422403,423501,423301,422202,422301,422205,423106,422103,422001,422401,423501,422401,422403,422204,422502,423106,422202,423401,422403,423401,422215,423102,422403,423117,422210,423202,422606,422005,422211,423101,422208,423402,423106,422205,423106,423502,422211,422208,422007,423213,422212,423303,422211,422001,422003,423212,424109,422201,423101,423502,422606,422210,423301,423301,422215,423205,423110,423206,423105,422209,422204,422101,423401,422203,423204,422001,422009,423201,423102,422104,423105,422204,423502,423301,422606,423101,423104,422003,422001,423202,422103,422209,422204,422305,423105,422308,423502,423401,423501,422203,422212,423102,422401,422501,422401,422103,422202,422202,422004,422403,423403,423213,423201,423106,423117,422103,422209,422208,422303,423401,423101,422202,422302,422606,422203,422305,423205,422103,422203,422011,423204,423208,423401,423202,422303,423205,422006,422006,422001,422203,422222,423501,423101,422402,423106,422203,423110,422002,423202,422606,422403,422305,423502,422103,422005,423104,422204,422001,422211,422208,422211,422211,423106,423106,423101,422112,422101,422403,422113,422101,422202,422007,422001,423502,422101,423303,422101,423202,424109,423401,423104,422001,422003,423106,422206,423106,422203,422206,422209,422002,422101,423101,423301,422308,422202,423106,422003,422204,422402,423105,423501,423104,423502,423301,422606,423117,423102,423301,422208,422210,423105,422202,423301,422209,423106,423206,423104,422303,422001,423208,422402,422202,423101,423301,423301,422304,422206,423117,423104,422403,423105,422402,423206,423301,422213,422606,423301,423301,423102,422204,422204,422004,423212,422403,422205,422202,422305,422201,422211,423403,423204,422103,423102,423301,422203,422208,422206,422103,422208,422206,422402,422202,422306,423204,423502,422303,423105,422211,422203,422208,423110,422308,423104,423502,422303,423403,423403,422401,422001,423213,422202,422306,423301,422103,422209,422210,422202,422001,422001,423213,422213,422003,422215,422203,422208,423105,423203,423104,423203,423212,423102,423403,423106,422103,422208,423101,422211,422001,423104,423104,423501,422606,423203,423102,422209,422003,423403,422215,423212,422004,423102,422210,422208,422004,422104,422206,423106,422211,423501,422403,423401,423301,423202,422403,422003,423201,423301,422403,422103,422204,423401,423104,423403,422101,422303,422202,422202,423204,422001,422305,422303,422308,423501,422606,422502,423501,422001,422001,422001,422101,422102,424109,422101,423101,422202,422202,423212,423212,422103,423401,423104,422303,422208,422606,423301,423501,422001,422206,422221,422207,422204,422302,423501,423502,422306,422209,423105,422202,422001,423205,422001,423501,422209,422202,422211,422101,422101,422103,422003,422215,422502,422211,422103,424109,422215,422103,423101,422010,422104,423201,423401,422103,422208,423301,422103,423202,422012,422209,422401,422213,423401,422010,422101,423102,423202,422004,424109,422306,423502,422301,422301,422215,422003,423206,423106,422007,423101,422003,423104,423403,423205,422001,423102,422308,422003,423108,423501,422001,423111,422306,422203,423105,422305,423501,423106,422209,422403,423212,422211,422212,423203,422402,422203,423501,422211,423204,422303,423301,423301,422211,422007,423208,423401,423208,423301,422013,422104,423402,422210,423105,422401,422104,423101,423213,422401,423105,422402,422101,422205,423104,422210,422206,422204,423401,423501,423204,423208,423111,422303,422502,422215,422211,422103,423101,423303,423401,422103,423208,422103,422205,423105,422302,423402,422211,422208,422105,423202,423208,422306,422403,422403,422212,422213,423104,422004,423401,423301,422210,423303,423105,423204,422204,423401,423301,423301,423205,423110,422202,423105,422212,422212,422213,422008,422402,422005,422304,422209,422211,422211,422003,423110,423402,422208,423104,423204,423106,423117,422103,423101,423206,423105,423104,422502,423111,422401,423206,422101,422306,422214,423105,423206,422304,422215,422202,423104,423301,422205,423301,423108,423106,422212,423401,422010,422203,422305,422502,423206,423301,423301,423101,423301,422006,422206,422203,422403,423202,422403,422003,422203,423102,422305,423106,422402,422202,422202,423501,422403,422104,422212,422222,423401,423401,423212,423208,422212,423205];
                    if($address!=null){
                        $distance = $this->getDistanceFromLatLonInKm($address->latitude,$address->longitude,19.9851074,73.743269);
                        if(in_array($address->pincode,$nashikPincodeArray)){
                            if($response['product_total_weight']<1) {
                                $response['delivery_charge'] = 25;
                            }
                            else{
                                $response['delivery_charge'] = round(ceil($response['product_total_weight'])*25,2);
                            }
                        }elseif ($this->startsWith($address->pincode,'40') || $this->startsWith($address->pincode,'41') || $this->startsWith($address->pincode,'42')||$this->startsWith($address->pincode,'43')||$this->startsWith($address->pincode,'44')){
                            if($response['product_total_weight']<1) {
                                $response['delivery_charge'] = 40;
                            }
                            else{
                                $response['delivery_charge'] = round(ceil($response['product_total_weight'])*40,2);
                            }
                        }else{
                            if($response['product_total_weight']<1) {
                                $response['delivery_charge'] = 70;
                            }
                            else{
                                $response['delivery_charge'] = round(ceil($response['product_total_weight'])*70,2);
                            }
                        }
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
                    'total'=>0,
                    'grinding_charge'=>0,
                    'product_total_weight'=>0];
                $allProductsInCart = $request->get('products');
                foreach($allProductsInCart as $key=>$eachProduct){
                    $product=ProductVariable::with('product')->where('id','=',$eachProduct['productVariableId'])->first();
                    if($product!=null){
                        $response['sub_total']=$response['sub_total']+($eachProduct['productQuantity']*$product->variable_selling_price);
                        $response['product_total_weight']=round($response['product_total_weight']+($eachProduct['productQuantity']*$product->product_variable_option_size), 2);
                        if(array_key_exists('customiseProductTypeOfMirchi',$eachProduct)  && $eachProduct['isCustomiseProduct']==1){
                            switch ($eachProduct['customiseProductTypeOfMirchi']) {
                                case "Medium Mirchi":
                                    $response['sub_total']=$response['sub_total']+($product['medium_mirchi_price']*$eachProduct['productQuantity']);
                                    break;
                                case "Spicy Mirchi":
                                    $response['sub_total']=$response['sub_total']+($product['spicy_mirchi_price']*$eachProduct['productQuantity']);
                                    break;
                                case "Mirchi":
                                    $response['sub_total']=$response['sub_total']+($product['less_mirchi_price']*$eachProduct['productQuantity']);
                                    break;
                            }
                            $response['grinding_charge']=$response['grinding_charge']+($product['grinding_price']*$eachProduct['productQuantity']);
                            $response['sub_total']=$response['sub_total']+$response['grinding_charge'];
                        }
                    }




                }

                if($request->has('address_id')){
                    $address = Address::find($request->address_id);
                    $nashikPincodeArray=[422012,423502,422003,423201,423208,422209,422215,423301,423301,422004,422211,423202,422212,422206,422208,422010,422010,423204,422003,422001,422212,422001,422203,423402,423402,422213,423104,423202,423301,422208,422003,423303,423205,422212,422403,423501,423301,422202,422301,422205,423106,422103,422001,422401,423501,422401,422403,422204,422502,423106,422202,423401,422403,423401,422215,423102,422403,423117,422210,423202,422606,422005,422211,423101,422208,423402,423106,422205,423106,423502,422211,422208,422007,423213,422212,423303,422211,422001,422003,423212,424109,422201,423101,423502,422606,422210,423301,423301,422215,423205,423110,423206,423105,422209,422204,422101,423401,422203,423204,422001,422009,423201,423102,422104,423105,422204,423502,423301,422606,423101,423104,422003,422001,423202,422103,422209,422204,422305,423105,422308,423502,423401,423501,422203,422212,423102,422401,422501,422401,422103,422202,422202,422004,422403,423403,423213,423201,423106,423117,422103,422209,422208,422303,423401,423101,422202,422302,422606,422203,422305,423205,422103,422203,422011,423204,423208,423401,423202,422303,423205,422006,422006,422001,422203,422222,423501,423101,422402,423106,422203,423110,422002,423202,422606,422403,422305,423502,422103,422005,423104,422204,422001,422211,422208,422211,422211,423106,423106,423101,422112,422101,422403,422113,422101,422202,422007,422001,423502,422101,423303,422101,423202,424109,423401,423104,422001,422003,423106,422206,423106,422203,422206,422209,422002,422101,423101,423301,422308,422202,423106,422003,422204,422402,423105,423501,423104,423502,423301,422606,423117,423102,423301,422208,422210,423105,422202,423301,422209,423106,423206,423104,422303,422001,423208,422402,422202,423101,423301,423301,422304,422206,423117,423104,422403,423105,422402,423206,423301,422213,422606,423301,423301,423102,422204,422204,422004,423212,422403,422205,422202,422305,422201,422211,423403,423204,422103,423102,423301,422203,422208,422206,422103,422208,422206,422402,422202,422306,423204,423502,422303,423105,422211,422203,422208,423110,422308,423104,423502,422303,423403,423403,422401,422001,423213,422202,422306,423301,422103,422209,422210,422202,422001,422001,423213,422213,422003,422215,422203,422208,423105,423203,423104,423203,423212,423102,423403,423106,422103,422208,423101,422211,422001,423104,423104,423501,422606,423203,423102,422209,422003,423403,422215,423212,422004,423102,422210,422208,422004,422104,422206,423106,422211,423501,422403,423401,423301,423202,422403,422003,423201,423301,422403,422103,422204,423401,423104,423403,422101,422303,422202,422202,423204,422001,422305,422303,422308,423501,422606,422502,423501,422001,422001,422001,422101,422102,424109,422101,423101,422202,422202,423212,423212,422103,423401,423104,422303,422208,422606,423301,423501,422001,422206,422221,422207,422204,422302,423501,423502,422306,422209,423105,422202,422001,423205,422001,423501,422209,422202,422211,422101,422101,422103,422003,422215,422502,422211,422103,424109,422215,422103,423101,422010,422104,423201,423401,422103,422208,423301,422103,423202,422012,422209,422401,422213,423401,422010,422101,423102,423202,422004,424109,422306,423502,422301,422301,422215,422003,423206,423106,422007,423101,422003,423104,423403,423205,422001,423102,422308,422003,423108,423501,422001,423111,422306,422203,423105,422305,423501,423106,422209,422403,423212,422211,422212,423203,422402,422203,423501,422211,423204,422303,423301,423301,422211,422007,423208,423401,423208,423301,422013,422104,423402,422210,423105,422401,422104,423101,423213,422401,423105,422402,422101,422205,423104,422210,422206,422204,423401,423501,423204,423208,423111,422303,422502,422215,422211,422103,423101,423303,423401,422103,423208,422103,422205,423105,422302,423402,422211,422208,422105,423202,423208,422306,422403,422403,422212,422213,423104,422004,423401,423301,422210,423303,423105,423204,422204,423401,423301,423301,423205,423110,422202,423105,422212,422212,422213,422008,422402,422005,422304,422209,422211,422211,422003,423110,423402,422208,423104,423204,423106,423117,422103,423101,423206,423105,423104,422502,423111,422401,423206,422101,422306,422214,423105,423206,422304,422215,422202,423104,423301,422205,423301,423108,423106,422212,423401,422010,422203,422305,422502,423206,423301,423301,423101,423301,422006,422206,422203,422403,423202,422403,422003,422203,423102,422305,423106,422402,422202,422202,423501,422403,422104,422212,422222,423401,423401,423212,423208,422212,423205];
                    if($address!=null){
                        $distance = $this->getDistanceFromLatLonInKm($address->latitude,$address->longitude,19.9851074,73.743269);
                        if(in_array($address->pincode,$nashikPincodeArray)){
                            if($response['product_total_weight']<1) {
                                $response['delivery_charge'] = 25;
                            }
                            else{
                                $response['delivery_charge'] = round(ceil($response['product_total_weight'])*25,2);
                            }
                        }elseif ($this->startsWith($address->pincode,'40') || $this->startsWith($address->pincode,'41') || $this->startsWith($address->pincode,'42')||$this->startsWith($address->pincode,'43')||$this->startsWith($address->pincode,'44')){
                            if($response['product_total_weight']<1) {
                                $response['delivery_charge'] = 40;
                            }
                            else{
                                $response['delivery_charge'] = round(ceil($response['product_total_weight'])*40,2);
                            }
                        }else{
                            if($response['product_total_weight']<1) {
                                $response['delivery_charge'] = 70;
                            }
                            else{
                                $response['delivery_charge'] = round(ceil($response['product_total_weight'])*70,2);
                            }
                        }
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
                $response['total']=round($response['total'], 2);


                $order= new Order;
                $order->Invoice_No = 'Inv-'.time();
                $order->total_amount = $response['total'];
                $order->sub_total = $response['sub_total'];
                $order->discount = $response['discount'];
                $order->grinding_charge = $response['grinding_charge'];
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

                $customer=Customer::where('mobile_no','=',trim($request->mobile_no))->first();
                if ($customer==null) {
                    return response()->json(['success' => false,
                        'msg'=>'No User Found'], 200);
                } else {
                    $order->customer_id = $customer->id;
                }
                $order->address_id=$request->address_id;
                $order->order_status_id=1;
                $orderSaveStatus=$order->save();

//                dd($response['total']*100);
                if($orderSaveStatus){

//                    $api_key="rzp_test_rY0vOBNRFJeB5T";
//                    $api_secret="k7cLsdpjeVgOScaSy9iT21qc";
                    $api = new Api($this->api_key, $this->api_secret);


// Orders
                    $razorOrder  = $api->order->create(array('receipt' => $order->Invoice_No, 'amount' => intval($response['total']*100), 'currency' => 'INR',
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
                            if(array_key_exists('customiseProductTypeOfMirchi',$eachProduct)){
                                $orderDetails->mirchiType=$eachProduct['customiseProductTypeOfMirchi'];
                            }
                            $orderDetails->save();


                            if( array_key_exists('isCustomiseProduct',$eachProduct) && $eachProduct['isCustomiseProduct']==1){
                                $allIngradient = $eachProduct['ingredients'];

                                foreach($allIngradient as $key=>$msalaIngradient){

                                    $newCustomizedProduct =MasalaIngradients::where('id','=',$msalaIngradient['ingredientId'])->first();
//                                    print_r($msalaIngradient['ingredientId']);
                                    if($newCustomizedProduct!=null){
                                        $newCustomeProductDetails = new CustomOrderDetails;
                                        $newCustomeProductDetails->ingradient_id=$msalaIngradient['ingredientId'];
                                        $newCustomeProductDetails->required_qty=$msalaIngradient['customiseProductQuantity'];
                                        $newCustomeProductDetails->order_details_id=$orderDetails->id;
                                        $newCustomeProductDetails->save();
                                }

                                }

                            }
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
        define( 'API_ACCESS_KEY', 'AAAAn5pEcwU:APA91bHu-WVF70SFm9I7JRi4nSY-1IAwhHnxEfSLXxc-NJANFscBcfhxbfQwcu1n-6FZjnL7zXNu-1079KakSnXtySJAbcyZ9RtskNA3Hp2B_SUlnSsSciQKKdx8UKYb_TmFsOLC-xRj' ); // get API access key from Google/Firebase API's Console
        try {
//            $api_key=env('RAZOR_PAY_TEST_KEY');
//            $api_secret=env('RAZOR_PAY_TEST_SECRETE');


            $api = new Api($this->api_key, $this->api_secret);
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
                        $msg = array
                        (
                            "body"=>"New Order Received On Shree Kakaji Masale App",
                            "title"=> "New Order Received"

                        );
                        $fields = array
                        (
                            'to'      => "/topics/NewOrderNotification",
                            'notification'                  => $msg
                        );

                        $headers = array
                        (
                            'Authorization: key=' . API_ACCESS_KEY,
                            'Content-Type: application/json'
                        );

                        $ch = curl_init();
                        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' ); //For firebase, use https://fcm.googleapis.com/fcm/send

                        curl_setopt( $ch,CURLOPT_POST, true );
                        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
                        $result = curl_exec($ch );
                        curl_close( $ch );
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
                $customer=Customer::where('mobile_no','=',trim($request->mobile_no))->first();

                if ($customer==null) {
                    return response()->json(['success' => false,
                        'msg'=>'No User Found'], 200);
                } else {
                    $customerOrders = Order::with(['couponDetails','customer','orderDetails','orderDetails.productVariable','orderDetails.productVariable.product','orderStatus','address','deliveryBoy','orderDetails.customProductDetails','orderDetails.customProductDetails.ingradient'])->where('customer_id','=',$customer->id)->orderBy('id','DESC')->get();

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
                $order = Order::where('id',$request->order_id)->with(['couponDetails','customer','orderDetails','orderStatus','address','deliveryBoy','orderDetails.customProductDetails','orderDetails.customProductDetails.ingradient'])->first();
                $data = compact($order);
                $html = view('admin.orders.invoice', compact('order'))->render();
//                dd($html);
//                $pdf = App::make('dompdf.wrapper');
//                PDF::setOptions(['dpi' => 150, 'defaultFont' => 'DejaVu Sans']);
//                $invPDF = $pdf->loadHTML($html);
//                return $pdf->download('invoice.pdf');
//                $pdf = PDF::loadHTML('admin.orders.invoice',compact('order'));
//                Mail::send(array(), array(), function ($message) use ($html) {
//                    $message->to('mataleshubham@gmail.com')
//                    ->subject('invoice')
//                    ->setBody($html, 'text/html');
//});
                Mail::to('mataleshubham@gmail.com')->queue(new SendMail($order));
                return response()->json(['success' => false,'data'=>"fcsd"], 200);;
//                return $pdf->download('invoice.pdf');
            }

        }catch (Exception $e) {
            return response()->json(['success' => false,
                'data'=>$e], 200);
        }
    }

    function startsWith ($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }
}
