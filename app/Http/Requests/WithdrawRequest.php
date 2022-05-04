<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends FormRequest
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
            'bank' => 'required',
            'money' => 'required',
            'note' => 'required',
            'otp_code' => 'nullable',
            'otp_hash' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'bank.required' => 'Hãy lựa chọn thẻ ngân hàng muốn rút!',
            'money.required' => 'Số tiền không hợp lệ!',
            'note.required' => 'Ghi chú là bắt buộc!'
        ];
    }
}
