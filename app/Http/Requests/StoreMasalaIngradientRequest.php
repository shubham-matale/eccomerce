<?php

namespace App\Http\Requests;

use App\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreMasalaIngradientRequest extends FormRequest
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
            'price'=>[
                'required'
            ],
            'hindiText'=>[
                'required'
            ],
            'marathiText'=>[
                'required'
            ]
        ];
    }
}
