<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class PasswordRequest extends FormRequest
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
            'password' => 'required|confirmed',
        ];
    }

    public function withValidator($validators)
    {
        $validators->after(function ($validator) {
            if(Hash::check($this->password_current, user()->getAuthPassword())){
                $validator->errors()->add('password_current', 'Mật khẩu hiện tại không chính xác!');
            }
        });
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Mật khẩu không được trống!',
            'password.min' => 'Mật khẩu phải chứa ít nhất :min ký tự!',
            'password.confirmed' => 'Mật khẩu không trùng khớp!'
        ];
    }
}
