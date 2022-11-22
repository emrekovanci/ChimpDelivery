<?php

namespace App\Http\Requests\AppStoreConnect;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\AlphaDashDot;

class StoreBundleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bundle_id' => [
                'required',
                new AlphaDashDot(),
                'max:255',
            ],

            'bundle_name' => [
                'required',
                'regex:/^[a-zA-Z0-9\s]+$/',
                'max:255',
            ],
        ];
    }

    public function messages() : array
    {
        return [
            'bundle_name.regex' => 'bundle_name can only contains alpha-numeric characters and space!',
        ];
    }
}
