@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        View Product Category
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>
                        Category Name
                    </th>
                    <td>
                        {{$productCategory->product_category_name}}
                    </td>
                </tr>
                <tr>
                    <th>
                        Image
                    </th>
                    <td>
                        <img width="400" alt="categoryImage" src="{{$productCategory->product_category_image_url}}">
                    </td>
                </tr>
                <tr>
                    <th>
                        Status
                    </th>
                    <td>
                        <label class=" {{$productCategory->is_active?'badge badge-danger':'badge badge-danger'}}">{{$productCategory->is_active?'Active':'Inactive'}}</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        Related Sub Categories
                    </td>
                    <td>
                        @foreach($productCategory->productSubcategory as $key=>$subCategory)
                            <p>{{$subCategory->product_subcategory_name}}</p>
                        @endforeach
                    </td>

                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
