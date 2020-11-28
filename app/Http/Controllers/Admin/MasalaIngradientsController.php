<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LanguageTranslation;
use Illuminate\Http\Request;
use App\Http\Requests\MassDestroyProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\StoreMasalaIngradientRequest;
use App\Product;
use App\ProductSubCategory;
use App\ProductVariableOption;
use App\masalaIngradients;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\ProductImage;
use App\ProductVariable;


class MasalaIngradientsController extends Controller
{
    public function index()
    {
        abort_unless(\Gate::allows('product_access'), 403);

        $products = MasalaIngradients::all();
        return view('admin.masalaIngradients.index', compact('products'));
    }

    public function create()
    {
        abort_unless(\Gate::allows('product_create'), 403);

        return view('admin.masalaIngradients.create');
    }

    public function store(StoreMasalaIngradientRequest $request)
    {
        abort_unless(\Gate::allows('product_create'), 403);
        $product = new MasalaIngradients;
        $product->name=$request->name;
        $product->quantity=100;
        $product->price=$request->price;
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


        return redirect()->route('admin.masalaIngradients.index');
    }

    public function edit($product)
    {

        abort_unless(\Gate::allows('product_edit'), 403);
        $product = MasalaIngradients::find($product);

        $languageData = LanguageTranslation::where('englishText','=',$product->name)->first();
        return view('admin.masalaIngradients.edit', compact(['product','languageData']));
    }

    public function update(StoreMasalaIngradientRequest $request, $product)
    {
        abort_unless(\Gate::allows('product_edit'), 403);
//        $product = new Product;
        $product = MasalaIngradients::find($product);

        $product->name=$request->name;
        $product->price=$request->price;
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

        return redirect()->route('admin.masalaIngradients.index');
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
        MasalaIngradients::whereIn('id', request('ids'))->delete();

        return response(null, 204);
    }



}
