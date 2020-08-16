<?php
namespace App\Http\Controllers\Api\V1\Admin;


use App\Customer;
use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use Illuminate\Support\Facades\Auth;
use Hash;



class DeliveryBoyController extends Controller{

    public function login(Request $request){
//       dd($request->all());
        try {

            $validator = Validator::make($request->all(), [
                'email'=>'required|email',
                'password'=>'required',
            ]);
            if($validator->fails()){
                return response()->json(['success' => false,
                    'msg'=>$validator->errors()], 200);
            }
            else{
                $credentials = $request->only('email', 'password');
                if (! $token = JWTAuth::attempt($credentials)) {
//                    return response()->json(['error' => 'invalid_credentials'], 400);
                    return response()->json(['success' => false, 'msg'=>'Please enter correct Username and Password'], 200);
                }
                $current_user= Auth::user();
                return response()->json(['success' => true, 'data' => ['token'=>$token,'user'=>$current_user]], 200);
            }
        }catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

    }

    public function getOrders(Request $request){
        try {
            $user=Auth::user();
            if($user==null){
                return response()->json(['success' => false,
                    'msg'=>'Session Expired'], 500);
            }
            else {
                $deliveryOrders = Order::with(['couponDetails','customer','orderDetails','orderDetails.productVariable','orderDetails.productVariable.product','orderStatus','address','deliveryBoy'])->where('order_status_id',3)->where('delivery_boy_id','=',$user->id)->orderBy('id','DESC')->get();
                if($deliveryOrders->isEmpty()){
                    return response()->json(['success' => false,
                        'msg'=>'No Orders Found',
                        'data'=>[]], 200);
                }
                return response()->json(['success' => true,
                    'data'=>$deliveryOrders], 200);
            }
        }catch (Exception $e) {
            return response()->json(['success' => false,
                'data'=>$e], 200);
        }
    }

    public function pastOrdersDelivered(Request $request){
        try {
            $user=Auth::user();
            if($user==null){
                return response()->json(['success' => false,
                    'msg'=>'Session Expired'], 500);
            }
            else {
                $deliveryOrders = Order::with(['couponDetails','customer','orderDetails','orderDetails.productVariable','orderDetails.productVariable.product','orderStatus','address','deliveryBoy'])->where('order_status_id',4)->where('delivery_boy_id','=',$user->id)->orderBy('id','DESC')->get();
                if($deliveryOrders->isEmpty()){
                    return response()->json(['success' => false,
                        'msg'=>'No Orders Found',
                        'data'=>[]], 200);
                }
                return response()->json(['success' => true,
                    'data'=>$deliveryOrders], 200);
            }
        }catch (Exception $e) {
            return response()->json(['success' => false,
                'data'=>$e], 200);
        }
    }

    public function deliverOrder(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'order_id'=>'required'
            ]);
            if($validator->fails()){
                return response()->json(['success' => false,
                    'msg'=>$validator->errors()], 200);
            }
            else {
                 $order= Order::find($request->order_id);
                 $order->order_status_id=4;
                 $orderUpdate=$order->save();
                if(!$orderUpdate){
                    return response()->json(['success' => false,
                        'msg'=>'Order Delivery Not Updated',
                        'data'=>[]], 200);
                }
                return response()->json(['success' => true,
                    'msg'=>'Order Status Updated'], 200);
            }
        }catch (Exception $e) {
            return response()->json(['success' => false,
                'data'=>$e], 200);
        }
    }

    public function changePassword(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'old_password' => 'required',
                'new_password' => 'required|string|min:6|confirmed',
                'new_password_confirmation' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['success' => false,
                    'msg'=>$validator->errors()], 200);
            }
            else {
                if (!(Hash::check($request->get('old_password'), Auth::user()->password))) {
                    return response()->json(['success' => false,
                        'msg'=>'Old Password is incorrect.',
                        'data'=>[]], 200);
                }
                //uncomment this if you need to validate that the new password is same as old one

                if(strcmp($request->get('old_password'), $request->get('new_password')) == 0){
                    return response()->json(['success' => false,
                        'msg'=>'New and old password are same',
                        'data'=>[]], 200);
                }
                $user = Auth::user();
                $user->password = Hash::make($request->get('new_password'));
                $changePasswordStatus = $user->save();

                if($changePasswordStatus){
                    return response()->json(['success' => true,
                        'msg'=>'Password Changed Successfully'], 200);
                }else{
                    return response()->json(['success' => false,
                        'msg'=>'Password Not Changed'], 200);
                }


            }
        }catch (Exception $e) {
            return response()->json(['success' => false,
                'data'=>$e], 200);
        }
    }
}


