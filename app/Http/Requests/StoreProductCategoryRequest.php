<?php

namespace App\Http\Requests;

use App\productCategory;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Gate::allows('create_product_category');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_category_name' => [
                'required',
            ],
            'product_category_image_url'=> [
                'required',
                'mimes:jpeg,bmp,png,jpg',
                'file',
                'max:1024'
            ],'hindiText'=>[
                'required'
            ],
            'marathiText'=>[
                'required'
            ]
        ];
    }




}
