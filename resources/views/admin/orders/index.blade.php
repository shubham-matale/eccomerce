@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Orders
        </div>

        <div class="card-body">
    <div class="col-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="false">New Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">In Delivery Process</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="true">Delivered</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#custom-tabs-one-settings" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">Unpaid Orders</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade active show" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                        <div class="table-responsive">
                            <table class=" table table-bordered table-striped table-hover datatable">
                                <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        Invoice No
                                    </th>
                                    <th>
                                        Total Amount
                                    </th>
                                    <th>
                                        Customer Name
                                    </th>
                                    <th>
                                        Coupon Applied
                                    </th>
                                    <th>
                                        Created At
                                    </th>
                                    <th>
                                        Action
                                    </th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $key => $order)
                                    @if($order->payment_status=='paid' && $order->order_status_id==2)
                                        <tr>
                                            <td></td>
                                            <td>{{$order->Invoice_No}}</td>
                                            <td>{{$order->total_amount}}</td>
                                            <th>{{$order->customer->name}}</th>
                                            <th ><span class="badge badge-success">{{$order->couponDetails!==null?$order->couponDetails->code:''}}</span></th>
                                            <td>{{$order->created_at}}</td>
                                            <td>
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.orders.show', $order->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endif

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                        <div class="table-responsive">
                            <table class=" table table-bordered table-striped table-hover datatable">
                                <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        Invoice No
                                    </th>
                                    <th>
                                        Total Amount
                                    </th>
                                    <th>
                                        Customer Name
                                    </th>
                                    <th>
                                        Coupon Applied
                                    </th>
                                    <th>
                                        Created At
                                    </th>
                                    <th>
                                        Action
                                    </th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $key => $order)
                                    @if($order->payment_status=='paid' && $order->order_status_id==3)
                                        <tr>
                                            <td></td>
                                            <td>{{$order->Invoice_No}}</td>
                                            <td>{{$order->total_amount}}</td>
                                            <th>{{$order->customer->name}}</th>
                                            <th ><span class="badge badge-success">{{$order->couponDetails!==null?$order->couponDetails->code:''}}</span></th>
                                            <td>{{$order->created_at}}</td>
                                            <td>
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.orders.show', $order->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endif

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade " id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">
                        <div class="table-responsive">
                            <table class=" table table-bordered table-striped table-hover datatable">
                                <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        Invoice No
                                    </th>
                                    <th>
                                        Total Amount
                                    </th>
                                    <th>
                                        Customer Name
                                    </th>
                                    <th>
                                        Coupon Applied
                                    </th>
                                    <th>
                                        Created At
                                    </th>
                                    <th>
                                        Action
                                    </th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $key => $order)
                                    @if($order->payment_status=='paid' && $order->order_status_id==4)
                                        <tr>
                                            <td></td>
                                            <td>{{$order->Invoice_No}}</td>
                                            <td>{{$order->total_amount}}</td>
                                            <th>{{$order->customer->name}}</th>
                                            <th ><span class="badge badge-success">{{$order->couponDetails!==null?$order->couponDetails->code:''}}</span></th>
                                            <td>{{$order->created_at}}</td>
                                            <td>
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.orders.show', $order->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endif

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                        <div class="table-responsive">
                            <table class=" table table-bordered table-striped table-hover datatable">
                                <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        Invoice No
                                    </th>
                                    <th>
                                        Total Amount
                                    </th>
                                    <th>
                                        Customer Name
                                    </th>
                                    <th>
                                        Coupon Applied
                                    </th>
                                    <th>
                                        Created At
                                    </th>
                                    <th>
                                        Action
                                    </th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $key => $order)
                                    @if($order->payment_status=='unpaid' && $order->order_status_id==1)
                                        <tr>
                                            <td></td>
                                            <td>{{$order->Invoice_No}}</td>
                                            <td>{{$order->total_amount}}</td>
                                            <th>{{$order->customer->name}}</th>
                                            <th ><span class="badge badge-success">{{$order->couponDetails!==null?$order->couponDetails->code:''}}</span></th>
                                            <td>{{$order->created_at}}</td>
                                            <td>
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.orders.show', $order->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endif

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
    </div>
    <div class="card">
        <div class="card-header">
            Orders
        </div>

        <div class="card-body">
            <div class="table-responsive">
{{--                <table class=" table table-bordered table-striped table-hover datatable">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th width="10">--}}
{{--                        </th>--}}
{{--                        <th>--}}

{{--                        </th>--}}
{{--                        <th>--}}
{{--                        </th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @foreach($permissions as $key => $permission)--}}
{{--                        <tr data-entry-id="{{ $permission->id }}">--}}
{{--                            <td>--}}

{{--                            </td>--}}
{{--                            <td>--}}
{{--                                {{ $permission->title ?? '' }}--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                @can('permission_show')--}}
{{--                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.permissions.show', $permission->id) }}">--}}
{{--                                        {{ trans('global.view') }}--}}
{{--                                    </a>--}}
{{--                                @endcan--}}
{{--                                @can('permission_edit')--}}
{{--                                    <a class="btn btn-xs btn-info" href="{{ route('admin.permissions.edit', $permission->id) }}">--}}
{{--                                        {{ trans('global.edit') }}--}}
{{--                                    </a>--}}
{{--                                @endcan--}}
{{--                                @can('permission_delete')--}}
{{--                                    <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">--}}
{{--                                        <input type="hidden" name="_method" value="DELETE">--}}
{{--                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
{{--                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">--}}
{{--                                    </form>--}}
{{--                                @endcan--}}
{{--                            </td>--}}

{{--                        </tr>--}}
{{--                    @endforeach--}}
{{--                    </tbody>--}}
{{--                </table>--}}
            </div>
        </div>
    </div>
@endsection

        @section('scripts')
            @parent
            <script>
                $(function () {
                    {{--let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'--}}
                    {{--let deleteButton = {--}}
                    {{--    text: deleteButtonTrans,--}}
                    {{--    url: "{{ route('admin.permissions.massDestroy') }}",--}}
                    {{--    className: 'btn-danger',--}}
                    {{--    action: function (e, dt, node, config) {--}}
                    {{--        var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {--}}
                    {{--            return $(entry).data('entry-id')--}}
                    {{--        });--}}

                    {{--        if (ids.length === 0) {--}}
                    {{--            alert('{{ trans('global.datatables.zero_selected') }}')--}}

                    {{--            return--}}
                    {{--        }--}}

                    {{--        if (confirm('{{ trans('global.areYouSure') }}')) {--}}
                    {{--            $.ajax({--}}
                    {{--                headers: {'x-csrf-token': _token},--}}
                    {{--                method: 'POST',--}}
                    {{--                url: config.url,--}}
                    {{--                data: { ids: ids, _method: 'DELETE' }})--}}
                    {{--                .done(function () { location.reload() })--}}
                    {{--        }--}}
                    {{--    }--}}
                    {{--}--}}
                    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                    {{--@can('permission_delete')--}}
                    {{--dtButtons.push(deleteButton)--}}
                    {{--@endcan--}}

                    $('.datatable:not(.ajaxTable)').DataTable({ buttons: dtButtons })
                })

            </script>
@endsection
