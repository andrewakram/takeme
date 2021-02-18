<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'role' => 'required|in:user,company',
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required|numeric',
            'password' => 'required|min:6',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'commercial_register'=>'',
            'image'=>''
        ];
    }
}
