@extends('layouts.admin')
@section('content')
@can('create_product_category')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.banners.create") }}">
                Add Banner
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        Banner
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            Banner Image
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banners as $key => $banner)
                        <tr data-entry-id="{{ $banner->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{$banner->imageUrl}}" target="_blank"><img src="{{$banner->imageUrl}}" height="150"></a>
                            </td>
                            <td>
                                {{$banner->status?'Active':'Inactive'}}
                            </td>
                            <td>
                                @can('product_category_access')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.banners.show', $banner->id) }}">
                                        <i class="fa fa-folder">
                                        </i>{{ trans('global.view') }}
                                    </a>
                                @endcan
                                @can('update_product_category')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.banners.edit', $banner->id) }}">
                                        <i class="fa fa-pencil-alt">
                                        </i>{{ trans('global.edit') }}
                                    </a>
                                @endcan
                                @can('delete_product_category')
                                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    url: "{{ route('admin.productsCategory.massDestroy') }}",
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
