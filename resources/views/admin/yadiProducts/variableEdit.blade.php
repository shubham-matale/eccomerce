@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} Variable
        </div>

        <div class="card-body">
            <form action="{{ route("admin.yadiProducts.updateVariable", [$productVariable->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <input hidden name="product_id" id="product_id" value="{{$productVariable->product_id}}">
                    <div class="form-group col-4">
                        <label>Product Size</label>
                        <select class="form-control" name="product_variable_option_id" id="product_variable_option_id" >
                            @foreach($productVariableOptions as $key=> $productVariableOption )
                                <option {{($productVariable->product_variable_option_size==$productVariableOption->variable_quantity)?'selected':''}}  value="{{$productVariableOption->id}}">{{$productVariableOption->variable_name}} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-4 {{ $errors->has('variable_original_price') ? 'has-error' : '' }}">
                        <label for="variable_original_price">Actual Price*</label>
                        <input type="number" id="variable_original_price" name="variable_original_price" class="form-control" value="{{ old('variable_original_price', isset($productVariable) ? $productVariable->variable_original_price : '') }}" step="0.01" required>
                        @if($errors->has('variable_original_price'))
                            <p class="help-block">
                                {{ $errors->first('variable_original_price') }}
                            </p>
                        @endif

                    </div>
                    <div class="form-group col-4 {{ $errors->has('variable_selling_price') ? 'has-error' : '' }}">
                        <label for="variable_selling_price">Selling Price*</label>
                        <input type="number" id="variable_selling_price" name="variable_selling_price" class="form-control" value="{{ old('variable_selling_price', isset($productVariable) ? $productVariable->variable_selling_price : '') }}" step="0.01" required>
                        @if($errors->has('variable_selling_price'))
                            <p class="help-block">
                                {{ $errors->first('variable_selling_price') }}
                            </p>
                        @endif

                    </div>
                    <div class="form-group col-md-4 {{ $errors->has('quantity') ? 'has-error' : '' }}">
                        <label for="price">Quantity*</label>
                        <input type="number" id="quantity" name="quantity" required min="1" class="form-control" value="{{ old('quantity', isset($productVariable) ? $productVariable->quantity : '') }}" step="0.01">
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
                <div>
                    <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                </div>
            </form>
        </div>
    </div>

@endsection
