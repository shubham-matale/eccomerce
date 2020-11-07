@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Product Category Edit {{$productCategory->id}}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.productsCategory.update", [$productCategory->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="form-group  col-md-3{{ $errors->has('product_category_name') ? 'has-error' : '' }}">
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
                <div class="form-group col-md-3 {{ $errors->has('hindiText') ? 'has-error' : '' }}">
                    <label for="hindiText">Hindi Text*</label>
                    <input type="text" id="hindiText" name="hindiText" class="form-control" value="{{ old('hindiText', isset($languageData) ? $languageData->hindiText : '') }}">
                    @if($errors->has('hindiText'))
                        <p class="help-block">
                            {{ $errors->first('hindiText') }}
                        </p>
                    @endif
                    <p class="helper-block">
                        {{ trans('global.product.fields.name_helper') }}
                    </p>
                </div>
                <div class="form-group col-md-3 {{ $errors->has('marathiText') ? 'has-error' : '' }}">
                    <label for="marathiText">Marathi Text*</label>
                    <input type="text" id="marathiText" name="marathiText" class="form-control" value="{{ old('marathiText', isset($languageData) ? $languageData->marathiText : '') }}">
                    @if($errors->has('marathiText'))
                        <p class="help-block">
                            {{ $errors->first('marathiText') }}
                        </p>
                    @endif
                    <p class="helper-block">
                        {{ trans('global.product.fields.name_helper') }}
                    </p>
                </div>

                <div class="form-group col-md-3 {{ $errors->has('product_category_image_url') ? 'has-error' : '' }}">
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
                <div class="form-group col-md-3">
                    <label>Is Active</label>
                    <select class="form-control" name="is_active" id="is_active" value="{{ old('is_active', isset($productCategory) ? $productCategory->is_active : 1) }}">
                        <option {{isset($productCategory) && $productCategory->is_active==1?'selected':''}}  value="1">Yes </option>
                        <option  {{isset($productCategory) && $productCategory->is_active==0?'selected':''}} value="0">No</option>

                    </select>
                </div>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>

@endsection
