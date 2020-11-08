@extends('layouts.admin')
@section('content')
    <section class="content-header">
        <div class="row">
            <div class="col-8">
                <h1>Dashboard</h1>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label>Date range:</label>

                    <div class="input-group">
                        <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fa fa-calendar"></i>
                      </span>
                        </div>
                        <input type="text"  name="daterange" class="form-control float-right" >
                    </div>
                    <!-- /.input group -->
                </div>
            </div>
        </div>


    </section>
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-blue"><i class="fa fa-shopping-bag"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">New Orders</span>
                        <span id="newOrderCount" class="info-box-number">{{$homeData["newOrderCount"]}}</span>
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
                        <span id="customerCount" class="info-box-number">{{$homeData["customerCount"]}}</span>
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
                        <span id="inDeliveryOrderCount" class="info-box-number">{{$homeData["inDeliveryOrderCount"]}}</span>
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
                        <span id="deliveredCount" class="info-box-number">{{$homeData["deliveredCount"]}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>


            <!-- /.col -->

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-inr"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Income</span>
                        <span id="totalIncome" class="info-box-number">{{$homeData["totalIncome"]}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-inr"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Income(without GST)</span>
                        <span id="subTotal" class="info-box-number">{{$homeData["subTotal"]}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
        </div>
        <!-- /.row -->


        <!-- /.row -->
    </section>
@endsection
@section('scripts')
@parent
<script>


    $(function() {
        $('input[name="daterange"]').daterangepicker({
            opens: 'left',
            maxDate: moment()
        }, function(start, end, label) {

        });

    });

    $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {

        let startDate=picker.startDate.format('YYYY-MM-DD')
        let endDate=picker.endDate.format('YYYY-MM-DD')

        let form = new FormData();
        form.append("startDate", startDate);
        form.append("endDate", endDate);
        data={
            "startDate":startDate,
            "endDate":endDate
        }
        $.ajax({url: window.location.origin+'/api/dashboardData',data:data,
            success: function(result){
                if(result['success']){
                    for(let tempData in result['data']){
                        console.log(tempData)
                        $('#'+tempData).text(result['data'][tempData])
                    }
                }else{
                    console.log('error',result)
                }
            },
            error:function (result) {
                alert("Something Went Wrong");
            }
        });
    });


</script>
@endsection
