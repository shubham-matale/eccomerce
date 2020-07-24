<?php

namespace App\Http\Requests;

use App\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return \Gate::allows('product_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'product_subcategory_id'=>[
                'required'
            ],
            'product_image_url'=> [
                'required',
                'mimes:jpeg,bmp,png,jpg',
                'file',
                'max:1024'
            ],
        ];
    }
}
