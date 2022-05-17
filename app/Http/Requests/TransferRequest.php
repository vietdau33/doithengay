<?php

namespace App\Http\Requests;

use App\Models\OtpData;
use App\Models\SystemSetting;
use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    private array $config = [];

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->config = $config = SystemSetting::getSettingWithFeature('transfer');
        $money = (int)$this->money;
        $this->merge([
            'money' => !empty($this->money) ? $money : $this->money,
            'total_money' => $money + (int)$config['transfer_fee_fix'] + ($money * (double)$config['transfer_fee'] / 100)
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_receive' => 'required|exists:users,username',
            'money' => 'required',
            'content' => 'required'
        ];
    }

    public function withValidator($validators)
    {
        $validators->after(function ($validator) {
            if (!empty($this->user_receive) && $this->user_receive == user()->username) {
                $validator->errors()->add('user_receive', 'Bạn không thể chuyển tiền cho chính mình!');
            }
            if (user()->security_level_2 === 1) {
                if (empty($this->otp_hash) || empty($this->otp_code)) {
                    $validator->errors()->add('otp_code', 'Bạn chưa nhập mã OTP!');
                } elseif (!OtpData::verify($this->otp_hash, $this->otp_code)) {
                    $validator->errors()->add('otp_code', 'Mã OTP không khớp!');
                }
            }
            if (!empty($this->money)) {
                if ($this->money < (int)$this->config['transfer_money_min']) {
                    $validator->errors()->add('money', 'Số tiền chuyển nhỏ hơn số tiền tối thiểu!');
                } elseif ($this->money > (int)$this->config['transfer_money_max']) {
                    $validator->errors()->add('money', 'Số tiền chuyển lớn hơn số tiền tối đa trong 1 lần chuyển!');
                } elseif ((int)user()->money < $this->total_money) {
                    $validator->errors()->add('money', 'Số tiền trong tài khoản không đủ!');
                }
            }
            if($this->config['transfer_turns_on_day'] != '00' && (int)$this->config['transfer_turns_on_day'] <= user()->count_number_trasnfer) {
                $validator->errors()->add('btn-transfer', 'Bạn đã hết số lần chuyển tiền trong ngày. Hãy thực hiện lại vào ngày mai!');
            }
        });
    }

    public function messages(): array
    {
        return [
            'user_receive.required' => 'Bạn phải nhập username người nhận!',
            'user_receive.exists' => 'Username bạn nhập không tồn tại!',
            'money.required' => 'Số tiền không được trống!',
            'content.required' => 'Hãy nhập nội dung chuyển tiền!'
        ];
    }
}
