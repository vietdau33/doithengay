<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BuyCardRequest extends FormRequest
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
            'card_buy' => ['required'],
            'money_buy' => ['required'],
            'method_buy' => ['required'],
            'type_buy' => ['required'],
            'quantity' => ['required', 'numeric', 'min:1']
        ];
    }

    public function messages(): array
    {
        return [
            'card_buy.required' => 'Bạn phải chọn loại thẻ!',
            'money_buy.required' => 'Bạn phải chọn mệnh giá thẻ!',
            'method_buy.required' => 'Bạn phải chọn phương thức thanh toán!',
            'type_buy.required' => 'Bạn phải chọn phương thức xử lý!',
            'quantity.required' => 'Hãy nhập số lượng thẻ cần mua!',
            'quantity.numeric' => 'Số lượng thẻ phải là một số hợp lệ!',
            'quantity.min' => 'Số lượng thẻ thấp nhất là 1!',
        ];
    }
}
