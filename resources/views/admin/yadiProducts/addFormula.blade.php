@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} Variable Details
        </div>

        <div class="card-body">
            <div class="row">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>
                            Ingredient Name
                        </th>
                        <th>
                            Ingredient Quantity(grams)
                        </th>
                        <th>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($customIngradients as $key=>$customVariable)
                        <tr>
                            <th>
                                {{$customVariable->ingradient->name}}
                            </th>
                            <th>
                                {{$customVariable->default_value}}
                            </th>

                            <th>
                                @can('product_delete')
                                    <form action="{{ route('admin.yadiProducts.deleteIngradientFromFormula', $customVariable->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
            <div class="form-group">
                <form name="add_name" id="add_name">


                    <div class="alert alert-danger print-error-msg" style="display:none">
                        <ul></ul>
                    </div>


                    <div class="alert alert-success print-success-msg" style="display:none">
                        <ul></ul>
                    </div>


                    <div class="table-responsive">
                        <table class="table table-bordered" id="dynamic_field">
                            <tr>
                                <td>Ingradient Name</td>
                                <td>Quantity(grams)*</td>
                                <td></td>
                            </tr>
                            <tr>

                                <td><select class="form-control" name="ingradient_id[]" id="ingradient_id" >
                                        @foreach($ingradients as $key=> $ingradient )
                                            <option   value="{{$ingradient->id}}">{{$ingradient->name}} </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" id="default_value" name="default_value[]" class="form-control" value="{{ old('default_value', isset($productVariable) ? $productVariable->variable_original_price : '') }}" step="1" required>
                                </td>
                                <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>
                            </tr>
                        </table>

                        @csrf
                        <input hidden name="product_id" id="product_id" value="{{$productVariable->product_id}}">
                        <input hidden name="productVaribaleId" id="productVaribaleId" value="{{$productVariable->id}}">
                        <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />
                    </div>


                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
@parent
<script type="text/javascript">
    $(document).ready(function(){
        var postURL = "{{route("admin.yadiProducts.saveFormula")}}";
        var i=1;


        $('#add').click(function(){
            i++;
            $('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added">' +
                '<td><select class="form-control" name="ingradient_id[]" id="ingradient_id" > @foreach($ingradients as $key=> $ingradient )<option value="{{$ingradient->id}}">{{$ingradient->name}}</option> @endforeach </select></td><td> <input type="number" id="default_value" name="default_value[]" class="form-control" value="" step="1" required></td>' +
                '<td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td>'+
                '</tr>');

        });


        $(document).on('click', '.btn_remove', function(){
            var button_id = $(this).attr("id");
            $('#row'+button_id+'').remove();
        });


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $('#submit').click(function(){
            $.ajax({
                url:postURL,
                method:"POST",
                data:$('#add_name').serialize(),
                type:'json',
                success:function(data)
                {
                    if(data.error){
                        printErrorMsg(data.error);
                    }else{
                        i=1;
                        $('.dynamic-added').remove();
                        $('#add_name')[0].reset();
                        $(".print-success-msg").find("ul").html('');
                        $(".print-success-msg").css('display','block');
                        $(".print-error-msg").css('display','none');
                        $(".print-success-msg").find("ul").append('<li>Record Inserted Successfully.</li>');

                        location.reload();
                    }
                }
            });
        });


        function printErrorMsg (msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display','block');
            $(".print-success-msg").css('display','none');
            $.each( msg, function( key, value ) {
                $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            });
        }
    });
</script>
@endsection
