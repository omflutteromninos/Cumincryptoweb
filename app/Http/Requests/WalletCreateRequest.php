<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WalletCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->coin_type == 'LTCT') {
            $rules = [
                'wallet_name' => 'required|max:100',
                'coin_type' => 'required'
            ];
        } else {
            $rules = [
                'wallet_name' => 'required|max:100',
                'coin_type' => 'required|exists:coins,type'
            ];
        }
        if(co_wallet_feature_active())
        $rules['type'] = 'required|in:'.PERSONAL_WALLET.','.CO_WALLET;

        return $rules;
    }

    public function messages()
    {
        return [
          'wallet_name.required' => __('Pocket name is required'),
          'type.required' => __('Pocket type is required'),
          'type.in' => __('Invalid pocket type'),
          'coin_type.required' => __('Coin type is required'),
          'coin_type.exists' => __('Invalid coin type'),
        ];
    }
}
