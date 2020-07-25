<?php

namespace App\Http\Controllers\Admin;

use App\CouponCode;
use App\Http\Controllers\Controller;

use App\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_unless(\Gate::allows('view_order'), 403);
        $orders = Order::with('couponDetails')->get();
        dd($orders);
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_unless(\Gate::allows('create_coupon'), 403);
        $couponCode = new CouponCode;
        return view('admin.coupons.create', compact('couponCode'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_unless(\Gate::allows('create_coupon'), 403);
        $couponCode = new CouponCode;
        $couponCode->create($request->all());
        return redirect()->route('admin.coupons.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CouponCode  $couponCode
     * @return \Illuminate\Http\Response
     */
    public function show(CouponCode $couponCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CouponCode  $couponCode
     * @return \Illuminate\Http\Response
     */
    public function edit($id,CouponCode $couponCode)
    {
        abort_unless(\Gate::allows('update_product_category'), 403);
        $couponCode = CouponCode::find($id);
        return view('admin.coupons.edit', compact('couponCode'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CouponCode  $couponCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        abort_unless(\Gate::allows('update_coupon'), 403);
        $couponCode = CouponCode::find($id);
        $couponCode->update($request->all());
        return redirect()->route('admin.coupons.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CouponCode  $couponCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(CouponCode $couponCode)
    {
        //
    }
}
