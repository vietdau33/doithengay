<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SystemBankRequest extends FormRequest
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
            'bank_type' => 'required',
            'bank_info' => 'required',
            'bank_content' => 'required',
            'bank_min' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'bank_type.required' => 'Tên ngân hàng là bắt buộc',
            'bank_info.required' => 'Thông tin tài khoản là bắt buộc',
            'bank_content.required' => 'Nội dung chuyển khoản là bắt buộc',
            'bank_min.required' => 'Nạp tối thiểu là bắt buộc',
        ];
    }
}
