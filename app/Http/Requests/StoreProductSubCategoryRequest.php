<?php

namespace App\Http\Requests;

use App\productCategory;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductSubCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Gate::allows('create_product_subcategory');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_subcategory_name' => [
                'required',
            ],
            'product_subcategory_image_url'=> [
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
