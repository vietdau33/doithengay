<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillRequest extends FormRequest
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
            'type' => 'required',
            'vendor_id' => 'required',
            'money' => 'required',
            'bill_number' => 'required',
            'type_pay' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Loại thanh toán là bắt buộc!',
            'vendor_id.required' => 'Loại tài khoản là bắt buộc!',
            'money.required' => 'Số tiền thanh toán là bắt buộc!',
            'bill_number.required' => 'Số điện thoại/mã khách hàng là bắt buộc!',
            'type_pay.required' => 'Phương thức thanh toán là bắt buộc!',
        ];
    }
}
