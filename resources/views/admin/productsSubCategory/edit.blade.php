@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Product Sub Category Edit {{$productSubCategory->id}}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.productsSubCategory.update", [$productSubCategory->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('product_subcategory_name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('global.product.fields.name') }}*</label>
                <input type="text" id="product_subcategory_name" name="product_subcategory_name" class="form-control" value="{{ old('product_subcategory_name', isset($productSubCategory) ? $productSubCategory->product_subcategory_name : '') }}">
                @if($errors->has('product_subcategory_name'))
                    <p class="help-block">
                        {{ $errors->first('product_subcategory_name') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('global.product.fields.name_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('product_subcategory_image_url') ? 'has-error' : '' }}">
                <label for="product_subcategory_image_url">Category Image Url</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" accept=".jpeg,.bmp,.png,.jpg" class="custom-file-input" id="product_subcategory_image_url" name="product_subcategory_image_url">
                        <label class="custom-file-label" for="product_subcategory_image_url">Choose file</label>
                    </div>
                </div>
                @if($errors->has('product_subcategory_image_url'))
                    <p class="help-block">
                        {{ $errors->first('product_subcategory_image_url') }}
                    </p>
                @endif
            </div>
            <div class="form-group">
                <label>Parent Category</label>
                <select class="form-control" name="product_category_id" id="product_category_id" value="{{ old('product_category_id', isset($productSubCategory) ? $productSubCategory->product_category_id : 1) }}">
                    @foreach($productCategory as $key=> $parentCategory )
                        <option {{isset($productSubCategory) && $productSubCategory->product_category_id==$parentCategory->id?'selected':''}}  value="{{$parentCategory->id}}">{{$parentCategory->product_category_name}} </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Is Active</label>
                <select class="form-control" name="is_active" id="is_active" value="{{ old('is_active', isset($productSubCategory) ? $productSubCategory->is_active : 1) }}">
                    <option {{isset($productSubCategory) && $productSubCategory->is_active==1?'selected':''}}  value="1">Yes </option>
                    <option  {{isset($productSubCategory) && $productSubCategory->is_active==0?'selected':''}} value="0">No</option>

                </select>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>

@endsection
