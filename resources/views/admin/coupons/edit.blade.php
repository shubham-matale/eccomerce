@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Product Category Edit
    </div>

    <div class="card-body">
        <form action="{{ route("admin.coupons.update", [$couponCode->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="form-group col-4 {{ $errors->has('code') ? 'has-error' : '' }}">
                    <label for="name">Code*</label>
                    <input type="text" id="code" name="code" class="form-control" value="{{ old('code', isset($couponCode) ? $couponCode->code : '') }}">
                    @if($errors->has('code'))
                        <p class="help-block">
                            {{ $errors->first('code') }}
                        </p>
                    @endif
                    <p class="helper-block">
                        {{ trans('global.product.fields.name_helper') }}
                    </p>
                </div>
                <div class="form-group col-4 {{ $errors->has('value') ? 'has-error' : '' }}">
                    <label for="value">Value Of Code*</label>
                    <input type="number" id="value" name="value" class="form-control" value="{{ old('value', isset($couponCode) ? $couponCode->value : '') }}" step="0.01" required>
                    @if($errors->has('value'))
                        <p class="help-block">
                            {{ $errors->first('value') }}
                        </p>
                    @endif

                </div>

                <div class="form-group  col-4 {{ $errors->has('minimum_value') ? 'has-error' : '' }}">
                    <label for="minimum_value">Minimum Cart Value*</label>
                    <input type="number" id="minimum_value" name="minimum_value" class="form-control" value="{{ old('minimum_value', isset($couponCode) ? $couponCode->minimum_value : '') }}" step="0.01" required>

                    @if($errors->has('minimum_value'))
                        <p class="help-block">
                            {{ $errors->first('minimum_value') }}
                        </p>
                    @endif

                </div>


                <div class="form-group col-4  {{ $errors->has('from_date') ? 'has-error' : '' }}" >
                    <label for="from_date">Valid From Date*</label>
                    <input id="from_date" name="from_date" placeholder="yyyy-mm-dd" class="form-control" value="{{ old('from_date', isset($couponCode) ? $couponCode->from_date : '') }}" type="text"  required>
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-th"></span>
                    </div>
                    @if($errors->has('from_date'))
                        <p class="help-block">
                            {{ $errors->first('from_date') }}
                        </p>
                    @endif

                </div>

                <div class="form-group col-4 {{ $errors->has('to_date') ? 'has-error' : '' }}" >
                    <label for="to_date">Valid To Date*</label>
                    <input type="text"  id="to_date" name="to_date" placeholder="yyyy-mm-dd" class="form-control" value="{{ old('to_date', isset($couponCode) ? $couponCode->to_date : '') }}"  required>
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-th"></span>
                    </div>
                    @if($errors->has('to_date'))
                        <p class="help-block">
                            {{ $errors->first('to_date') }}
                        </p>
                    @endif

                </div>


                <div class="form-group col-4">
                    <label>Is Active</label>
                    <select class="form-control" name="is_active" id="is_active" value="{{ old('is_active', isset($couponCode) ? $couponCode->is_active : 1) }}">
                        <option {{isset($couponCode) && $couponCode->is_active==1?'selected':''}}  value="1">Yes </option>
                        <option  {{isset($couponCode) && $couponCode->is_active==0?'selected':''}} value="0">No</option>

                    </select>
                </div>
                <div class="form-group col-4">
                    <label>Value Type</label>
                    <select class="form-control" name="type" id="type" value="{{ old('type', isset($couponCode) ? $couponCode->type : 1) }}">
                        <option  {{isset($couponCode) && $couponCode->type=='percentage'?'selected':''}} value="percentage">Percent</option>
                        <option {{isset($couponCode) && $couponCode->type=='flat'?'selected':''}}  value="flat">Flat </option>

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
@section('scripts')
@parent
<script>
    $(document).ready(function(){
        var date_input=$('input[name="to_date"]'); //our date input has the name "date"
        var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
        var options={
            format: 'yyyy-mm-dd',
            container: container,
            todayHighlight: true,
            autoclose: true,
        };
        date_input.datepicker(options);

        var date_input=$('input[name="from_date"]'); //our date input has the name "date"
        var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
        var options={
            format: 'yyyy-mm-dd',
            container: container,
            todayHighlight: true,
            autoclose: true,
        };
        date_input.datepicker(options);
    })
</script>
@endsection
