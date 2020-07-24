@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('global.product.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.products.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-md-3 {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name">{{ trans('global.product.fields.name') }}*</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($product) ? $product->name : '') }}">
                    @if($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                    <p class="helper-block">
                        {{ trans('global.product.fields.name_helper') }}
                    </p>
                </div>
                <div class="form-group col-md-3">
                    <label>Product Sub Category*</label>
                    <select class="form-control" name="product_subcategory_id" id="product_subcategory_id" value="{{ old('is_active', isset($product) ? $product->product_subcategory_id : 1) }}">
                        @foreach($productSubCategory as $key=> $parentCategory )
                            <option  value="{{$parentCategory->id}}">{{$parentCategory->product_subcategory_name}} </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-3 {{ $errors->has('product_image_url') ? 'has-error' : '' }}">
                    <label for="product_image_url">Product Image </label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" accept=".jpeg,.bmp,.png,.jpg" class="custom-file-input" id="product_image_url" name="product_image_url">
                            <label class="custom-file-label" for="product_image_url">Choose file</label>
                        </div>
                    </div>
                    @if($errors->has('product_image_url'))
                        <p class="help-block">
                            {{ $errors->first('product_image_url') }}
                        </p>
                    @endif
                </div>

            </div>
            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                <label for="description">{{ trans('global.product.fields.description') }}</label>
                <textarea id="description" name="description" class="form-control ">{{ old('description', isset($product) ? $product->description : '') }}</textarea>
                @if($errors->has('description'))
                    <p class="help-block">
                        {{ $errors->first('description') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('global.product.fields.description_helper') }}
                </p>
            </div>

            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>
@endsection
