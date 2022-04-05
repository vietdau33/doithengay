<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TradeCardRequest extends FormRequest
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
            'card_type' => 'required',
            'card_money' => 'required',
            'card_serial' => 'required',
            'card_number' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'card_type.required' => 'Phải chọn loại thẻ!',
            'card_money.required' => 'Phải chọn mệnh giá thẻ!',
            'card_serial.required' => 'Phải nhập số Seri thẻ!',
            'card_number.required' => 'Phải nhập mã thẻ!',
        ];
    }
}
