<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\productCategory;

class UpdateProductCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Gate::allows('update_product_category');
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
                'nullable',
                'mimes:jpeg,bmp,png,jpg',
                'file',
                'max:1024'
            ],
        ];
    }
}
