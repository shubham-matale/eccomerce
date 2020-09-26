@extends('layouts.admin')
@section('content')
    <section class="content-header">
        <h1>
            Dashboard

        </h1>

    </section>
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-blue"><i class="fa fa-shopping-bag"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">New Orders</span>
                        <span class="info-box-number">{{$homeData["newOrderCount"]}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Customer</span>
                        <span class="info-box-number">{{$homeData["customerCount"]}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-bicycle"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">In Process Orders</span>
                        <span class="info-box-number">{{$homeData["inDeliveryOrderCount"]}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-check-circle-o"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Deliverd Orders</span>
                        <span class="info-box-number">{{$homeData["deliveredCount"]}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->


        <!-- /.row -->
    </section>
@endsection
@section('scripts')
@parent

@endsection
