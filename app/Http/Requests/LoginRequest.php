<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'username' => 'required|regex:/^[A-Za-z0-9\.]{4,16}$/i',
            'password' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Tài khoản không được trống!',
            'username.regex' => 'Tài khoản chỉ cho phép chữ thường, chữ hoa, số và dấu chấm!',
            'password.required' => 'Mật khẩu không được trống!'
        ];
    }
}
