@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Create Product Sub Category
    </div>

    <div class="card-body">
        <form action="{{ route("admin.productsCategory.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('product_category_name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('global.product.fields.name') }}*</label>
                <input type="text" id="product_category_name" name="product_category_name" class="form-control" value="{{ old('product_category_name', isset($productCategory) ? $productCategory->product_category_name : '') }}">
                @if($errors->has('product_category_name'))
                    <p class="help-block">
                        {{ $errors->first('product_category_name') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('global.product.fields.name_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('product_category_image_url') ? 'has-error' : '' }}">
                <label for="product_category_image_url">Category Image Url</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" accept=".jpeg,.bmp,.png,.jpg" class="custom-file-input" id="product_category_image_url" name="product_category_image_url">
                        <label class="custom-file-label" for="product_category_image_url">Choose file</label>
                    </div>
                </div>
                @if($errors->has('product_category_image_url'))
                    <p class="help-block">
                        {{ $errors->first('product_category_image_url') }}
                    </p>
                @endif
            </div>
            <div class="form-group">
                <label>Is Active</label>
                <select class="form-control" name="is_active" id="is_active" value="{{ old('is_active', isset($productCategory) ? $productCategory->is_active : 1) }}">
                    <option  value="1">Yes </option>
                    <option value="0">No</option>

                </select>
            </div>

            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>
@endsection
