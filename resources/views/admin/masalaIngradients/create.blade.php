@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} Masala Ingradient
    </div>

    <div class="card-body">
        <form action="{{ route("admin.masalaIngradients.store") }}" method="POST" enctype="multipart/form-data">
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
                <div class="form-group col-md-3 {{ $errors->has('price') ? 'has-error' : '' }}">
                    <label for="price">Price per kg*</label>
                    <input type="number" id="price" name="price" class="form-control" value="{{ old('price', isset($product) ? $product->price : '') }}">
                    @if($errors->has('price'))
                        <p class="help-block">
                            {{ $errors->first('price') }}
                        </p>
                    @endif
                    <p class="helper-block">
                        {{ trans('global.product.fields.name_helper') }}
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
