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
            'password_current' => 'required',
            'password' => 'required|confirmed',
        ];
    }

    public function withValidator($validators)
    {
        $validators->after(function ($validator) {
            if(mb_strlen($this->password) < 6) {
                return $validator->errors()->add('password', 'Mật khẩu mới phải chứa ít nhất 6 ký tự!');
            }
            if(!empty($this->password_current) && !Hash::check($this->password_current, user()->getAuthPassword())){
                return $validator->errors()->add('password_current', 'Mật khẩu hiện tại không chính xác!');
            }
            return true;
        });
    }

    public function messages(): array
    {
        return [
            'password_current.required' => 'Mật khẩu cũ không được trống!',
            'password.required' => 'Mật khẩu không được trống!',
            'password.confirmed' => 'Mật khẩu không trùng khớp!'
        ];
    }
}
