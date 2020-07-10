@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        View Product Sub Category
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>
                        Category Name
                    </th>
                    <td>
                        {{$productSubCategory->product_subcategory_name}}
                    </td>
                </tr>
                <tr>
                    <th>
                        Image
                    </th>
                    <td>
                        <img width="400" alt="categoryImage" src="{{$productSubCategory->product_subcategory_image_url}}">
                    </td>
                </tr>
                <tr>
                    <th>
                        Status
                    </th>
                    <td>
                        <label class=" {{$productSubCategory->is_active?'badge badge-danger':'badge badge-danger'}}">{{$productSubCategory->is_active?'Active':'Inactive'}}</label>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
