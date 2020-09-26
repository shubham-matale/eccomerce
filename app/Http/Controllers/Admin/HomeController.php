<?php

namespace App\Http\Controllers\Admin;

use App\Customer;
use App\Order;

class HomeController
{
    public function index()
    {
        $homeData=["nweOrders"=>0];
        $homeData["newOrderCount"]=Order::where('payment_status','paid')->where('order_status_id',2)->count();
        $homeData["inDeliveryOrderCount"]=Order::where('payment_status','paid')->where('order_status_id',3)->count();
        $homeData["deliveredCount"]=Order::where('payment_status','paid')->where('order_status_id',4)->count();
        $homeData["customerCount"]=Customer::all()->count();
        return view('home',compact('homeData'));


    }
}
