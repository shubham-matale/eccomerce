<?php

namespace App\Http\Controllers\Admin;

use App\Customer;
use App\Order;
use Illuminate\Http\Request;
use Validator;
use DB;
use Carbon\Carbon;
use Hash;
use Auth;

class HomeController
{
    public function index()
    {
        $startDate= date('Y-m-d');
        $endDate= date('Y-m-d');

        $homeData=["nweOrders"=>0];
        $homeData["newOrderCount"]=Order::where('payment_status','paid')->where('order_status_id',2)->whereDate('created_at', '>=',$startDate)->whereDate('created_at', '<=',$endDate)->count();
        $homeData["inDeliveryOrderCount"]=Order::where('payment_status','paid')->where('order_status_id',3)->whereDate('created_at', '>=',$startDate)->whereDate('created_at', '<=',$endDate)->count();
        $homeData["deliveredCount"]=Order::where('payment_status','paid')->where('order_status_id',4)->whereDate('created_at', '>=',$startDate)->whereDate('created_at', '<=',$endDate)->count();
        $homeData["customerCount"]=Customer::whereDate('created_at', '>=',$startDate)->whereDate('created_at', '<=',$endDate)->count();
        $homeData["totalIncome"]=DB::table('orders')->where('payment_status','paid')->whereDate('created_at', '>=',$startDate)->whereDate('created_at', '<=',$endDate)->sum('orders.total_amount');
        $homeData["subTotal"]=DB::table('orders')->where('payment_status','paid')->whereDate('created_at', '>=',$startDate)->whereDate('created_at', '<=',$endDate)->sum('orders.sub_total');

        return view('home',compact('homeData'));


    }

    public function dashboardData(Request $request){

        try {



            $validator = Validator::make($request->all(), [
                'startDate' => 'required',
                'endDate'=>'required'
            ]);
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }
            else{
                $startDate= date($request->startDate);
                $endDate= date($request->endDate);

                $homeData["newOrderCount"]=Order::where('payment_status','paid')->where('order_status_id',2)->whereDate('created_at', '>=',$startDate)->whereDate('created_at', '<=',$endDate)->count();
                $homeData["inDeliveryOrderCount"]=Order::where('payment_status','paid')->where('order_status_id',3)->whereDate('created_at', '>=',$startDate)->whereDate('created_at', '<=',$endDate)->count();
                $homeData["deliveredCount"]=Order::where('payment_status','paid')->where('order_status_id',4)->whereDate('created_at', '>=',$startDate)->whereDate('created_at', '<=',$endDate)->count();
                $homeData["customerCount"]=Customer::whereDate('created_at', '>=',$startDate)->whereDate('created_at', '<=',$endDate)->count();
                $homeData["totalIncome"]=DB::table('orders')->where('payment_status','paid')->whereDate('created_at', '>=',$startDate)->whereDate('created_at', '<=',$endDate)->sum('orders.total_amount');
                $homeData["subTotal"]=DB::table('orders')->where('payment_status','paid')->whereDate('created_at', '>=',$startDate)->whereDate('created_at', '<=',$endDate)->sum('orders.sub_total');




                return response()->json(['success' => true, 'data' => $homeData], 200);
            }
        }catch (Exception $e) {
            return response()->json(['success' => false, 'data' => $e], 400);
        }
    }

    public function showChangePasswordForm(){
        return view('admin.password.changePassword');
    }

    public function changePassword(Request $request){

        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with("success","Password changed successfully !");

    }
}
