<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsApiController extends Controller
{
    public function index(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'subcategory_id' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['success' => false,
                    'msg'=>$validator->errors()], 200);
            }
            else{
                $products = Product::where('product_subcategory_id','=',$request->subcategory_id)->with(['productVariable','productImages'])->get();
                return response()->json(['success' => true,
                    'data'=>$products], 200);
            }
        }catch (Exception $e) {
            return response()->json(['success' => false,
                'data'=>$e], 200);
        }


    }


}
