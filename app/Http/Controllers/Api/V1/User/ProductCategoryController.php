<?php
namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Product;
use App\ProductCategory;
use App\Banners;

class ProductCategoryController extends Controller{

    public function index()
    {
        try {

            $productCategory= ProductCategory::with('productSubCategory')->orderBy('displayOrder')->get();
            $banners= Banners::where('status',true)->get();
            if(count($productCategory)>0){
                return response()->json(['success' => true,
                    'data'=>$productCategory,'bannersData'=>$banners], 200);
            }else{
                return response()->json(['success' => false,
                    'msg'=>'No Category Found'], 200);
            }

        }catch (Exception $e) {
            return response()->json(['success' => fase,
                'data'=>$e], 200);
        }

    }
}
