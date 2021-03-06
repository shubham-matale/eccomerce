@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('global.product.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.products.update", [$product->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
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
                <div class="form-group col-md-3 {{ $errors->has('gst_percentage') ? 'has-error' : '' }}">
                    <label for="gst_percentage">GST Percentage*</label>
                    <input type="number" id="gst_percentage" name="gst_percentage" class="form-control" value="{{ old('marathiText', isset($product) ? $product->gst_percentage : 5) }}">
                    @if($errors->has('gst_percentage'))
                        <p class="help-block">
                            {{ $errors->first('gst_percentage') }}
                        </p>
                    @endif
                    <p class="helper-block">
                        {{ trans('global.product.fields.name_helper') }}
                    </p>
                </div>
                <div class="form-group col-md-3">
                    <label>Product Sub Category</label>
                    <select class="form-control" name="product_subcategory_id" id="product_subcategory_id" value="{{ old('is_active', isset($product) ? $product->product_subcategory_id : 1) }}">
                        @foreach($productSubCategory as $key=> $parentCategory )
                            <option {{isset($product) && $parentCategory->id==$product->product_subcategory_id?'selected':''}} value="{{$parentCategory->id}}">{{$parentCategory->product_subcategory_name}} </option>
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
            <div class="form-group  {{ $errors->has('description') ? 'has-error' : '' }}">
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
