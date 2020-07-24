<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use App\ProductCategory;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $productCategory = ProductCategory::all();

        return view('admin.productsCategory.index', compact('productCategory'));
    }

    public function create()
    {
        abort_unless(\Gate::allows('create_product_category'), 403);

        return view('admin.productsCategory.create');
    }

    public function store(StoreProductCategoryRequest $request)
    {
//        dd($request->all());
        abort_unless(\Gate::allows('create_product_category'), 403);
        $productCategory = new ProductCategory();
        if ($request->hasfile('product_category_image_url')){
            $productCategory->product_category_image_url=$this->saveImage($request->file('product_category_image_url'));
        }
        $productCategory->product_category_name=$request->input('product_category_name','');
        $productCategory->is_active=$request->input('is_active','');
        $productCategory->save();

        return redirect()->route('admin.productsCategory.index');
    }

    public function edit($id,ProductCategory $productCategory)
    {
        abort_unless(\Gate::allows('update_product_category'), 403);
        $productCategory=ProductCategory::find($id);
        return view('admin.productsCategory.edit', compact('productCategory'));
    }

    public function update(UpdateProductCategoryRequest $request, $id)
    {

        abort_unless(\Gate::allows('update_product_category'), 403);
        $productCategory=ProductCategory::find($id);
        if ($request->hasfile('product_category_image_url')){
            $productCategory->product_category_image_url=$this->saveImage($request->file('product_category_image_url'));
        }

        $productCategory->product_category_name=$request->input('product_category_name','');
        $productCategory->is_active=$request->input('is_active','');

        $productCategory->save();
        return redirect()->route('admin.productsCategory.index');
    }

    public function show($id)
    {

        $productCategory=ProductCategory::where('id','=',$id)->with('productSubCategory')->first();
        return view('admin.productsCategory.show', compact('productCategory'));
    }

    public function destroy($id )
    {
        abort_unless(\Gate::allows('delete_product_category'), 403);

        $productCategory=ProductCategory::find($id);
        $productCategory->delete();
        return back();
    }

    public function massDestroy(MassDestroyProductRequest $request)
    {
        Product::whereIn('id', request('ids'))->delete();
        return response(null, 204);
    }

    function saveImage($image){
        $image_name = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('images/productCategories');
        $image->move($destinationPath, $image_name);
        $imageURL=env('APP_URL').'/images/productCategories/'.$image_name;
        return $imageURL;
    }
}
