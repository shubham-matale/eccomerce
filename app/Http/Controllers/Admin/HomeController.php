<?php

namespace App\Http\Controllers\Admin;

use App\Customer;
use App\Order;
use Illuminate\Http\Request;
use Validator;
use DB;
use Carbon\Carbon;


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
}
