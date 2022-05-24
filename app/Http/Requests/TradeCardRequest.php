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
            'card_number' => 'required',
            'type_trade' => 'required',
        ];
    }

    public function withValidator($validators)
    {
        $validators->after(function ($validator) {
            if(empty($this->card_number)) {
                return false;
            }
            $lenCardNumber = strlen($this->card_number);
            switch ($this->card_type) {
                case 'viettel':
                    if($lenCardNumber != 13 && $lenCardNumber != 15) {
                        return $validator->errors()->add('card_number', 'Mã thẻ Viettel phải có 13 hoặc 15 chữ số!');
                    }
                    break;
                case 'vinaphone':
                    if($lenCardNumber != 12 && $lenCardNumber != 14) {
                        return $validator->errors()->add('card_number', 'Mã thẻ Vinaphone phải có 12 hoặc 14 chữ số!');
                    }
                    break;
                case 'mobifone':
                    if($lenCardNumber != 12) {
                        return $validator->errors()->add('card_number', 'Mã thẻ Mobifone phải có 12 chữ số!');
                    }
                    break;
                case 'vietnamobile':
                    if($lenCardNumber != 12) {
                        return $validator->errors()->add('card_number', 'Mã thẻ Vietnamobile phải có 12 chữ số!');
                    }
                    break;
            }
            return true;
        });
    }

    public function messages(): array
    {
        return [
            'card_type.required' => 'Phải chọn loại thẻ!',
            'card_money.required' => 'Phải chọn mệnh giá thẻ!',
            'card_serial.required' => 'Phải nhập số Seri thẻ!',
            'card_serial.regex' => 'Số Seri thẻ không hợp lệ!',
            'card_number.required' => 'Phải nhập mã thẻ!',
            'card_number.regex' => 'Mã thẻ không hợp lệ',
            'type_trade.required' => 'Phải chọn phương thức gạch thẻ!',
        ];
    }
}
