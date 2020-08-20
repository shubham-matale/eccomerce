@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('global.product.title') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>
                        {{ trans('global.product.fields.name') }}
                    </th>
                    <td>
                        {{ $product->name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.product.fields.description') }}
                    </th>
                    <td>
                        {!! $product->description !!}
                    </td>
                </tr>

            </tbody>
        </table>
        <div class="card mt-4">
            <div class="card-body">
                <div class="row ">
                    <div class="col-6">
                        <div style="margin-bottom: 10px;" class="row">
                            <div class="col-lg-12">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-variable">
                                    Add Product Variable
                                </button>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Variable Quantity
                                    </th>
                                    <th>
                                        Variable Actual Price
                                    </th>
                                    <th>
                                        Variable Selling Price
                                    </th>
                                    <th>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($productVariables as $key=>$productVariable)
                                <tr>
                                    <th>
                                        {{$productVariable->product_variable_options_name}}
                                    </th>
                                    <th>
                                        {{$productVariable->variable_original_price}}
                                    </th>
                                    <th>
                                        {{$productVariable->variable_selling_price}}
                                    </th>
                                    <th>
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.yadiProducts.addFormula', $productVariable->id) }}">
                                            Add Formula{{$productVariable->id}}
                                        </a>
                                        @can('product_edit')
                                            <a class="btn btn-xs btn-info" href="{{ route('admin.yadiProducts.editVariable', $productVariable->id) }}">
                                                {{ trans('global.edit') }}
                                            </a>
                                        @endcan
                                        @can('product_delete')
                                            <form action="{{ route('admin.yadiProducts.variableDestroy', $productVariable->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="product_id" value="{{$productVariable->product_id}}">
                                                <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                            </form>
                                        @endcan
                                    </th>
                                </tr>
                            @endforeach



                            </tbody>
                        </table>
                    </div>
                    <div class="col-6">
                        <div style="margin-bottom: 10px;" class="row">
                            <div class="col-lg-12">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-image">
                                    Add Product Images
                                </button>

                            </div>

                        </div>
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>
                                    Product Image
                                </th>
                                <th>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($productImages as $key=>$productImage)
                                <tr>
                                    <td>
                                        <a href="{{ $productImage->product_image_url ?? '#' }}" target="_blank">{{$productImage->product_image_url ?? '' }}</a>
                                    </td>


                                    <th>

                                        @can('product_delete')
                                            <form action="{{ route('admin.yadiProducts.imageDestroy', $productImage->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="product_id" value="{{$productImage->product_id}}">
                                                <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                            </form>
                                        @endcan
                                    </th>
                                </tr>
                            @endforeach



                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<div class="modal fade show" id="modal-variable" style="display: none; padding-right: 16px;" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Product Variable</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route("admin.yadiProducts.addVariable") }}" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                    @csrf
                    <div class="row">
                        <input hidden name="product_id" id="product_id" value="{{$product->id}}">
                        <div class="form-group col-4">
                            <label>Product Size</label>
                            <select class="form-control" name="product_variable_option_id" id="product_variable_option_id" >
                                @foreach($productVariableOptions as $key=> $productVariable )
                                    <option  value="{{$productVariable->id}}">{{$productVariable->variable_name}} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-4 {{ $errors->has('variable_original_price') ? 'has-error' : '' }}">
                            <label for="variable_original_price">Actual Price*</label>
                            <input type="number" id="variable_original_price" name="variable_original_price" class="form-control" value="{{ old('variable_original_price', isset($product) ? $product->variable_original_price : '') }}" step="0.01" required>
                            @if($errors->has('variable_original_price'))
                                <p class="help-block">
                                    {{ $errors->first('variable_original_price') }}
                                </p>
                            @endif

                        </div>
                        <div class="form-group col-4 {{ $errors->has('variable_selling_price') ? 'has-error' : '' }}">
                            <label for="variable_selling_price">Selling Price*</label>
                            <input type="number" id="variable_selling_price" name="variable_selling_price" class="form-control" value="{{ old('variable_selling_price', isset($product) ? $product->variable_selling_price : '') }}" step="0.01" required>
                            @if($errors->has('variable_selling_price'))
                                <p class="help-block">
                                    {{ $errors->first('variable_selling_price') }}
                                </p>
                            @endif

                        </div>
                        <div class="form-group col-md-4 {{ $errors->has('quantity') ? 'has-error' : '' }}">
                            <label for="price">Quantity*</label>
                            <input type="number" id="quantity" name="quantity" required min="1" class="form-control" value="{{ old('quantity', isset($product) ? $product->quantity : '') }}" step="0.01">
                            @if($errors->has('quantity'))
                                <p class="help-block">
                                    {{ $errors->first('quantity') }}
                                </p>
                            @endif
                            <p class="helper-block">
                                {{ trans('global.product.fields.price_helper') }}
                            </p>
                        </div>
                    </div>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade show" id="modal-image" style="display: none; padding-right: 16px;" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Product Variable</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route("admin.yadiProducts.addImage") }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <input hidden name="product_id" id="product_id" value="{{$product->id}}">
                        <div class="form-group {{ $errors->has('product_image') ? 'has-error' : '' }}">
                            <label for="product_image">Category Image Url</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" accept=".jpeg,.bmp,.png,.jpg" class="custom-file-input" id="product_image" name="product_image[]" required multiple>
                                    <label class="custom-file-label" for="product_image">Choose file</label>
                                </div>
                            </div>
                            @if($errors->has('product_image'))
                                <p class="help-block">
                                    {{ $errors->first('product_image') }}
                                </p>
                            @endif
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection
