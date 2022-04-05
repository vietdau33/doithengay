<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest
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
            'bank_select' => 'required',
            'account_number' => 'required',
            'account_name' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'bank_select.required' => 'Bạn phải lựa chọn loại thẻ!',
            'account_number.required' => 'Số tài khoản không được trống!',
            'account_name.required' => 'Tên chủ tài khoản không được trống!'
        ];
    }
}
