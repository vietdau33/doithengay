<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'fullname' => 'required',
            'username' => 'required|regex:/^[A-Za-z0-9\.]{4,16}$/i|unique:users',
            'password' =>  'required|min:5|confirmed',
            'email' => 'required|email:rfc|unique:users',
            'phone' => 'required|regex:/^(0)[0-9]{9,10}$/i|unique:users'
        ];
    }

    public function messages(): array
    {
        return [
            'fullname.required' => 'Tên không được để trống!',
            'username.required' => 'Tài khoản không được trống!',
            'username.regex' => 'Tài khoản chỉ cho phép chữ thường, chữ hoa, số và dấu chấm!',
            'username.unique' => 'Tên tài khoản đã được sử dụng!',
            'password.required' => 'Mật khẩu không được trống!',
            'password.min' => 'Mật khẩu phải chứa ít nhất :min ký tự!',
            'password.confirmed' => 'Mật khẩu không trùng khớp!',
            'email.required' => 'Email là bắt buộc!',
            'email.email' => 'Email không đúng định dạng!',
            'email.unique' => 'Email đã được sử dụng!',
            'phone.required' => 'Số điện thoại không được trống!',
            'phone.regex' => 'Số điện thoại không đúng định dạng!',
            'phone.unique' => 'Số điện thoại đã được sử dụng!'
        ];
    }
}
