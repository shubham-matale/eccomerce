<?php

namespace App\Http\Requests;

use App\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreLanguageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'englishText'=>[
                'required'
            ],'hindiText'=>[
                'required'
            ],
            'marathiText'=>[
                'required'
            ]
        ];
    }
}
