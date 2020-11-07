<?php

namespace App\Http\Controllers\Admin;

use App\CustomProductVariable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreYadiProductRequest;
use App\Http\Requests\UpdateYadiProductRequest;
use App\LanguageTranslation;
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

        return redirect()->route('admin.yadiProducts.index');
    }

    public function edit($id)
    {
        abort_unless(\Gate::allows('product_edit'), 403);
        $product=Product::find($id);
        $productSubCategory=ProductSubCategory::all();
        $languageData = LanguageTranslation::where('englishText','=',$product->name)->first();
        return view('admin.yadiProducts.edit', compact(['product','productSubCategory','languageData']));
    }

    public function update(UpdateYadiProductRequest $request, $id)
    {
        abort_unless(\Gate::allows('product_edit'), 403);
//      $product = new Product;

        $product=Product::find($id);

        $product->name=$request->name;
        $product->description=$request->description;

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
            'spicy_mirchi_price'         => 'required|numeric',
            'less_mirchi_price'         => 'required|numeric',
            'medium_mirchi_price'         => 'required|numeric',
            'grinding_price'         => 'required|numeric',
            'quantity'                   =>'required|numeric'
        ]);

        $productVariableOption = ProductVariableOption::find($request->product_variable_option_id);
        $productvariable = new ProductVariable;
        $productvariable->variable_original_price=$request->variable_original_price;
        $productvariable->variable_selling_price=$request->variable_selling_price;
        $productvariable->spicy_mirchi_price=$request->spicy_mirchi_price;
        $productvariable->less_mirchi_price=$request->less_mirchi_price;
        $productvariable->medium_mirchi_price=$request->medium_mirchi_price;
        $productvariable->grinding_price=$request->grinding_price;
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
        $productVariableOption = ProductVariableOption::find($request->product_variable_option_id);
        $productVariable->variable_original_price=$request->variable_original_price;
        $productVariable->variable_selling_price=$request->variable_selling_price;
        $productVariable->product_variable_options_name=$productVariableOption->variable_name;
        $productVariable->product_variable_option_size=$productVariableOption->variable_quantity;
        $productVariable->spicy_mirchi_price=$request->spicy_mirchi_price;
        $productVariable->less_mirchi_price=$request->less_mirchi_price;
        $productVariable->medium_mirchi_price=$request->medium_mirchi_price;
        $productVariable->grinding_price=$request->grinding_price;
        $productVariable->save();

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
        $productVariable=ProductVariable::with('customVariables','customVariables.ingradient')->where('id',$id)->first();
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
