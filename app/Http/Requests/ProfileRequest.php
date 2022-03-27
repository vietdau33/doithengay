<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
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
            'email' => ['required', 'email:rfc', Rule::unique('users')->ignore(user()->id)],
            'phone' => ['required', 'regex:/^(0)[0-9]{9,10}$/i', Rule::unique('users')->ignore(user()->id)]
        ];
    }

    public function messages(): array
    {
        return [
            'fullname.required' => 'Tên không được để trống!',
            'email.required' => 'Email là bắt buộc!',
            'email.email' => 'Email không đúng định dạng!',
            'email.unique' => 'Email đã được sử dụng!',
            'phone.required' => 'Số điện thoại không được trống!',
            'phone.regex' => 'Số điện thoại không đúng định dạng!',
            'phone.unique' => 'Số điện thoại đã được sử dụng!'
        ];
    }
}
