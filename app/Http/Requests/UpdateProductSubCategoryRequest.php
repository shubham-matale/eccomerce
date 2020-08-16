<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\productCategory;

class UpdateProductSubCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Gate::allows('update_product_subcategory');
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
                'nullable',
                'mimes:jpeg,bmp,png,jpg',
                'file',
                'max:1024'
            ],
        ];
    }
}
