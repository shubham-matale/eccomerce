<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductSubCategoryRequest;
use App\Http\Requests\UpdateProductSubCategoryRequest;
use App\ProductSubCategory;
use App\ProductCategory;

class ProductSubCategoryController extends Controller
{
    public function index()
    {


        $productSubCategory = ProductSubCategory::with('productCategory')->get();
//        dd($productSubCategory);
        return view('admin.productsSubCategory.index', compact('productSubCategory'));
    }

    public function create()
    {
        abort_unless(\Gate::allows('create_product_subcategory'), 403);
        $productCategory=ProductCategory::get();
        return view('admin.productsSubCategory.create', compact('productCategory'));
    }

    public function store(StoreProductSubCategoryRequest $request)
    {
        abort_unless(\Gate::allows('create_product_subcategory'), 403);
        $productSubCategory = new ProductSubCategory();
        if ($request->hasfile('product_subcategory_image_url')){
            $productSubCategory->product_subcategory_image_url=$this->saveImage($request->file('product_subcategory_image_url'));
        }

        $productSubCategory->product_subcategory_name=$request->input('product_subcategory_name','');
        $productSubCategory->product_category_id=$request->input('product_category_id','');
        $productSubCategory->is_active=$request->input('is_active','');
        $productSubCategory->save();

        return redirect()->route('admin.productsSubCategory.index');
    }

    public function edit($id,ProductSubCategory $productSubCategory)
    {
        abort_unless(\Gate::allows('update_product_category'), 403);
        $productSubCategory=ProductSubCategory::find($id);
        $productCategory=ProductCategory::get();
        return view('admin.productsSubCategory.edit', compact(['productSubCategory','productCategory']));
    }

    public function update(UpdateProductSubCategoryRequest $request, $id)
    {

        abort_unless(\Gate::allows('update_product_category'), 403);

        $productSubCategory=ProductSubCategory::find($id);
        if ($request->hasfile('product_subcategory_image_url')){
            $productSubCategory->product_subcategory_image_url=$this->saveImage($request->file('product_subcategory_image_url'));
        }

        $productSubCategory->product_subcategory_name=$request->input('product_subcategory_name','');
        $productSubCategory->product_category_id=$request->input('product_category_id','');
        $productSubCategory->is_active=$request->input('is_active','');
        $productSubCategory->save();
        return redirect()->route('admin.productsSubCategory.index');
    }

    public function show($id)
    {
        abort_unless(\Gate::allows('product_show'), 403);
        $productSubCategory=ProductSubCategory::find($id);
        return view('admin.productsSubCategory.show', compact('productSubCategory'));
    }

    public function destroy($id )
    {
        abort_unless(\Gate::allows('delete_product_category'), 403);

        $productSubCategory=ProductSubCategory::find($id);
        $productSubCategory->delete();
        return back();
    }

    public function massDestroy(MassDestroyProductRequest $request)
    {
        Product::whereIn('id', request('ids'))->delete();
        return response(null, 204);
    }

    function saveImage($image){
        $image_name = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('images/productSubCategories');
        $image->move($destinationPath, $image_name);
        $imageURL=env('APP_URL').'/images/productSubCategories/'.$image_name;
        return $imageURL;
    }
}
