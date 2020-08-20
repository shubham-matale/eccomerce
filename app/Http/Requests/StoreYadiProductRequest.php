<?php

namespace App\Http\Requests;

use App\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreYadiProductRequest extends FormRequest
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

            'product_image_url'=> [
                'mimes:jpeg,bmp,png,jpg',
                'file',
                'max:1024'
            ],
        ];
    }
}
