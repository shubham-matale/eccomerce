@extends('layouts.admin')
@section('content')
@can('create_coupon')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.coupons.create") }}">
                Add Coupon
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        Product Category List
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            Code
                        </th>
                        <th>
                            Value
                        </th>
                        <th>
                            Type
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Minimum Cart Price
                        </th>
                        <th>
                            From Date
                        </th>
                        <th>
                            To Date
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coupons as $key => $coupon)
                        <tr data-entry-id="{{ $coupon->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $coupon->code ?? '' }}
                            </td>
                            <td>
                               {{$coupon->value}}
                            </td>
                            <td>
                                {{$coupon->type}}
                            </td>
                            <td>
                                {{$coupon->is_active?'Active':'Inactive'}}
                            </td>
                            <td>
                                {{$coupon->minimum_value}}
                            </td>
                            <td>
                                {{$coupon->from_date}}
                            </td>
                            <td>
                                {{$coupon->to_date}}
                            </td>
                            <td>
{{--                                @can('access_coupon')--}}
{{--                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.coupons.show', $coupon->id) }}">--}}
{{--                                        <i class="fa fa-folder">--}}
{{--                                        </i>{{ trans('global.view') }}--}}
{{--                                    </a>--}}
{{--                                @endcan--}}
                                @can('update_coupon')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.coupons.edit', $coupon->id) }}">
                                        <i class="fa fa-pencil-alt">
                                        </i>{{ trans('global.edit') }}
                                    </a>
                                @endcan
                                @can('delete_coupon')
                                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.coupons.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('product_delete')
  dtButtons.push(deleteButton)
@endcan

  $('.datatable:not(.ajaxTable)').DataTable({ buttons: dtButtons })
})

</script>
@endsection
