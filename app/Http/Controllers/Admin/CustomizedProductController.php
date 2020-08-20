<?php

namespace App\Http\Controllers\Admin;

use App\CustomProductVariable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreYadiProductRequest;
use App\MasalaIngradients;
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
use Validator;


class CustomizedProductController extends Controller{
    public function index()
    {
        abort_unless(\Gate::allows('product_access'), 403);

        $products = Product::where('isCustomProduct',1)->get();
        return view('admin.yadiProducts.index', compact('products'));
    }

    public function create()
    {
        abort_unless(\Gate::allows('product_create'), 403);

        $productSubCategory=ProductSubCategory::all();
        return view('admin.yadiProducts.create',compact('productSubCategory'));
    }

    public function store(StoreYadiProductRequest $request)
    {
        abort_unless(\Gate::allows('product_create'), 403);
        $product = new Product;
        $product->product_image_url='';
        $product->isCustomProduct=true;
        $product->name=$request->name;
        $product->description=$request->description;
        $product->save();

        return redirect()->route('admin.yadiProducts.index');
    }

    public function edit(Product $product)
    {
        abort_unless(\Gate::allows('product_edit'), 403);
        $productSubCategory=ProductSubCategory::all();
        return view('admin.yadiProducts.edit', compact(['product','productSubCategory']));
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


        return redirect()->route('admin.yadiProducts.index');
    }

    public function show($id)
    {

        abort_unless(\Gate::allows('product_show'), 403);
        $product = Product::find($id);
        $productVariableOptions = ProductVariableOption::all();
        $productVariables=ProductVariable::where('product_id','=',$id)->get();
        $productImages=ProductImage::where('product_id','=',$id)->get();
        return view('admin.yadiProducts.show', compact(['product','productVariableOptions','productVariables','productImages']));
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

        return redirect()->route('admin.yadiProducts.show',$product->id);

    }

    public function variableEdit($id){
        abort_unless(\Gate::allows('product_edit'), 403);
        $productSubCategory=ProductSubCategory::all();
        $productVariableOptions = ProductVariableOption::all();
        $productVariable=ProductVariable::find($id);
        return view('admin.yadiProducts.variableEdit', compact(['productVariableOptions','productVariable']));
    }

    public function variableUpdate(Request $request,ProductVariable $productVariable){
        abort_unless(\Gate::allows('product_edit'), 403);
        $productVariable->update($request->all());
        $product=Product::find($request->product_id);
        return redirect()->route('admin.yadiProducts.show',$request->product_id);

    }


    public function variableDestroy(Request $request,$id){
        abort_unless(\Gate::allows('product_delete'), 403);

        $productVariable=ProductVariable::find($id);
        $productVariable->delete();
        $product=Product::find($request->product_id);
        return redirect()->route('admin.yadiProducts.show',$request->product_id);

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
        return redirect()->route('admin.yadiProducts.show',$request->product_id);

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
        return redirect()->route('admin.yadiProducts.show',$request->product_id);
    }

    public function showFormula($id){
        abort_unless(\Gate::allows('product_edit'), 403);

        $ingradients=MasalaIngradients::all();
        $productVariable=ProductVariable::with('customVariables','customVariables.ingradient')->first();
        $customIngradients=CustomProductVariable::with('ingradient')->where('product_variable_id',$id)->get();
        return view('admin.yadiProducts.addFormula', compact(['ingradients','productVariable','customIngradients']));
    }

    public function saveFormula(Request $request){
        abort_unless(\Gate::allows('product_edit'), 403);

        $rules = [];

        foreach($request->input('ingradient_id') as $key => $value) {
            $rules["ingradient_id.{$key}"] = 'required';
            $rules["default_value.{$key}"] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $ingradientId=[];
            $default_values=[];
            foreach($request->input('ingradient_id') as $key => $value) {
                $ingradientId[$key]=$value;
            }
            foreach($request->input('default_value') as $key => $value) {
                $default_values[$key]=$value;
            }
            for($i=0;$i<count($ingradientId);$i++) {
                $customVariableIngradient=new CustomProductVariable;
                $customVariableIngradient->product_variable_id=$request->productVaribaleId;

                $customVariableIngradient->ingradient_id=$ingradientId[$i];
                $customVariableIngradient->default_value=$default_values[$i];
                $customVariableIngradient->save();
            }


            return response()->json(['success'=>'done']);
        }


        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function deleteIngradientFromFormula(Request $request,$id){
        abort_unless(\Gate::allows('product_delete'), 403);

        $customVariableIngradient=CustomProductVariable::find($id);
        $productVaribaleId=$customVariableIngradient->product_variable_id;
        $customVariableIngradient->delete();
        return redirect()->route('admin.yadiProducts.addFormula',$productVaribaleId);
    }
}

?>
