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
        $cardType = array_keys(config('card.list'));
        $methodList = array_keys(config('payment.method'));
        $moneyList = [10000, 20000, 30000, 50000, 100000, 200000, 300000, 500000, 1000000];
        return [
            'card_buy' => ['required', Rule::in($cardType)],
            'money_buy' => ['required', Rule::in($moneyList)],
            'method_buy' => ['required', Rule::in($methodList)],
            'quantity' => ['required', 'numeric', 'min:1']
        ];
    }

    public function messages(): array
    {
        return [
            'card_buy.required' => 'Bạn phải chọn loại thẻ!',
            'card_buy.in' => 'Loại thẻ không hợp lệ hoặc không tồn tại!',
            'money_buy.required' => 'Bạn phải chọn mệnh giá thẻ!',
            'money_buy.in' => 'Mệnh giá thẻ không hợp lệ hoặc không tồn tại!',
            'method_buy.required' => 'Bạn phải chọn phương thức thanh toán!',
            'method_buy.in' => 'Phương thức thanh toán không hợp lệ hoặc không tồn tại!',
            'quantity.required' => 'Hãy nhập số lượng thẻ cần mua!',
            'quantity.numeric' => 'Số lượng thẻ phải là một số hợp lệ!',
            'quantity.min' => 'Số lượng thẻ thấp nhất là 1!',
        ];
    }
}
