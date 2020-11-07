<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LanguageTranslation;
use Illuminate\Http\Request;
use App\Http\Requests\MassDestroyProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Product;
use App\ProductSubCategory;
use App\ProductVariableOption;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\ProductImage;
use App\ProductVariable;


class ProductsController extends Controller
{
    public function index()
    {
        abort_unless(\Gate::allows('product_access'), 403);

        $products = Product::where('isCustomProduct',0)->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        abort_unless(\Gate::allows('product_create'), 403);

        $productSubCategory=ProductSubCategory::all();
        return view('admin.products.create',compact('productSubCategory'));
    }

    public function store(StoreProductRequest $request)
    {
        abort_unless(\Gate::allows('product_create'), 403);
        $product = new Product;
        if($request->has('product_image_url')){
            $product->product_image_url=$this->saveImage($request->product_image_url);
        }else{
            $product->product_image_url='';
        }

        $product->name=$request->name;
        $product->description=$request->description;
        $product->product_subcategory_id=$request->product_subcategory_id;
        $product->save();

        $languageData = LanguageTranslation::where('englishText','=',$request->name)->first();
        if($languageData){
            if(strlen($languageData->originalText)<=0){
                $languageData->originalText=strtolower(str_replace(' ', '_', $request->name));
            }
            $languageData->englishText=$request->name;
            $languageData->hindiText=$request->hindiText;
            $languageData->marathiText=$request->marathiText;
            $languageData->save();
        }else{
            $languageData = new LanguageTranslation;
            $languageData->originalText=strtolower(str_replace(' ', '_', $request->name));
            $languageData->englishText=$request->name;
            $languageData->hindiText=$request->hindiText;
            $languageData->marathiText=$request->marathiText;
            $languageData->save();
        }


        return redirect()->route('admin.products.index');
    }

    public function edit(Product $product)
    {
        abort_unless(\Gate::allows('product_edit'), 403);
        $productSubCategory=ProductSubCategory::all();
        $languageData = LanguageTranslation::where('englishText','=',$product->name)->first();
        return view('admin.products.edit', compact(['product','productSubCategory','languageData']));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        abort_unless(\Gate::allows('product_edit'), 403);
//        $product = new Product;
        if($request->hasFile('product_image_url')){
            $product->product_image_url=$this->saveImage($request->product_image_url);
        }

        $product->name=$request->name;
        $product->description=$request->description;
        $product->product_subcategory_id=$request->product_subcategory_id;
        $product->save();

        $languageData = LanguageTranslation::where('englishText','=',$product->name)->first();
        if($languageData){
            if(strlen($languageData->originalText)<=0){
                $languageData->originalText=strtolower(str_replace(' ', '_', $request->name));
            }
            $languageData->englishText=$request->name;
            $languageData->hindiText=$request->hindiText;
            $languageData->marathiText=$request->marathiText;
            $languageData->save();
        }else{
            $languageData = new LanguageTranslation;
            $languageData->originalText=strtolower(str_replace(' ', '_', $request->name));
            $languageData->englishText=$request->name;
            $languageData->hindiText=$request->hindiText;
            $languageData->marathiText=$request->marathiText;
            $languageData->save();
        }

        return redirect()->route('admin.products.index');
    }

    public function show(Product $product)
    {
        abort_unless(\Gate::allows('product_show'), 403);
        $productVariableOptions = ProductVariableOption::all();
        $productVariables=ProductVariable::where('product_id','=',$product->id)->get();
        $productImages=ProductImage::where('product_id','=',$product->id)->get();
        return view('admin.products.show', compact(['product','productVariableOptions','productVariables','productImages']));
    }

    public function destroy(Product $product)
    {
        abort_unless(\Gate::allows('product_delete'), 403);

        $product->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductRequest $request)
    {
        Product::whereIn('id', request('ids'))->delete();

        return response(null, 204);
    }

    public function variableCreate(Request $request){

        abort_unless(\Gate::allows('product_edit'), 403);

        $this->validate($request,[
            'product_variable_option_id' =>'required',
            'variable_original_price'    => 'required|numeric',
            'variable_selling_price'     => 'required|numeric',
            'quantity'                   =>'required|numeric'
        ]);

        $productVariableOption = ProductVariableOption::find($request->product_variable_option_id);
        $productvariable = new ProductVariable;
        $productvariable->variable_original_price=$request->variable_original_price;
        $productvariable->variable_selling_price=$request->variable_selling_price;
        $productvariable->product_variable_options_name=$productVariableOption->variable_name;
        $productvariable->product_variable_option_size=$productVariableOption->variable_quantity;
        $productvariable->quantity=$request->quantity;
        $productvariable->product_id=$request->product_id;
        $productvariable->save();
        $productVariableOptions = ProductVariableOption::all();
        $product=Product::find($request->product_id);

        $productVariables=ProductVariable::where('product_id','=',$product->id)->get();

        return redirect()->route('admin.products.show',compact('product'));

    }

    public function variableEdit($id){
        abort_unless(\Gate::allows('product_edit'), 403);
        $productSubCategory=ProductSubCategory::all();
        $productVariableOptions = ProductVariableOption::all();
        $productVariable=ProductVariable::find($id);
        return view('admin.products.variableEdit', compact(['productVariableOptions','productVariable']));
    }

    public function variableUpdate(Request $request,ProductVariable $productVariable){
        abort_unless(\Gate::allows('product_edit'), 403);
        $productVariableOption = ProductVariableOption::find($request->product_variable_option_id);
        $productVariable->variable_original_price=$request->variable_original_price;
        $productVariable->variable_selling_price=$request->variable_selling_price;
        $productVariable->product_variable_options_name=$productVariableOption->variable_name;
        $productVariable->product_variable_option_size=$productVariableOption->variable_quantity;
        $productVariable->save();
        $product=Product::find($request->product_id);
        return redirect()->route('admin.products.show',compact('product'));

    }


    public function variableDestroy(Request $request,$id){
        abort_unless(\Gate::allows('product_delete'), 403);

        $productVariable=ProductVariable::find($id);
        $productVariable->delete();
        $product=Product::find($request->product_id);
        return redirect()->route('admin.products.show',compact('product'));

    }

    public function imageCreate(Request $request){

        abort_unless(\Gate::allows('product_edit'), 403);

        $this->validate($request,[
            'product_image.*' =>'required|file|max:1024|mimes:jpeg,bmp,png,jpg',
        ]);

        if($request->has('product_image')) {

            $files = $request->file('product_image');

            foreach ($files as $key=>$image) {
                $productImage = new ProductImage;
                $productImage->product_image_url=$this->saveImage($image);
                $productImage->product_id=$request->product_id;
                $productImage->save();
            }
        }
        $product=Product::find($request->product_id);
        return redirect()->route('admin.products.show',compact('product'));

    }

    function saveImage($image){
            $image_name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('images/products/');
            $image->move($destinationPath, $image_name);
            $imageURL=env('APP_URL').'/images/products/'.$image_name;
            return $imageURL;
    }

    public function imageDestroy(Request $request,$id){

        abort_unless(\Gate::allows('product_delete'), 403);

        $productImage=ProductImage::find($id);
        $productImage->delete();
        $product=Product::find($request->product_id);
        return redirect()->route('admin.products.show',compact('product'));

    }

}
