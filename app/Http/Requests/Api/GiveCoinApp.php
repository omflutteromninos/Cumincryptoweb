<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GiveCoinApp extends FormRequest
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
        $rules = [
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => ['required','numeric','min:'.settings("minimum_withdrawal_amount"),'max:'.settings('maximum_withdrawal_amount')],
            'email' => 'required|exists:users,email'
        ];

        return $rules;
    }

    public function messages()
    {
        return  [
            'wallet_id.required' => __('Wallet Id field can not be empty'),
            'wallet_id.exists' => __("Wallet Id doesn't exists"),
            'email.required' => __('Email field can not be empty'),
            'email.email' => __('Invalid email address'),
            'amount.required' => __('Coin amount field can not be empty'),
            'amount.numeric' => __('Coin amount field must be numeric'),
            'amount.max' => __('Max coin amount exceeded'),
            'amount.min' => __('Coin amount minimum required')
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->header('accept') == "application/json") {
            $errors = [];
            if ($validator->fails()) {
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
            }
            $json = [
                'success'=>false,
                'message' => $errors[0],
            ];
            $response = new JsonResponse($json, 200);

            throw (new ValidationException($validator, $response))->errorBag($this->errorBag)->redirectTo($this->getRedirectUrl());
        } else {
            throw (new ValidationException($validator))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl());
        }

    }
}
