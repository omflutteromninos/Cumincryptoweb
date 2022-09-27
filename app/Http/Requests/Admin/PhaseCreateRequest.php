<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PhaseCreateRequest extends FormRequest
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
        $rules = [];
        $rules = [
            'phase_name' => 'required|max:255'
            ,'amount' => 'required|numeric|min:0.000000001'
            ,'start_date' => 'required|date|before:end_date'
            ,'end_date' => 'required|date|after:start_date'
            ,'rate' => 'required|numeric|min:0.000000001'
            ,'affiliation_level' => 'required|numeric|min:1|max:3'
            // ,'affiliation_percentage' => 'required|numeric|min:0.000000001|max:100'
           // ,'fees' => 'required|numeric|min:0.000000001|max:100'
            ,'bonus' => 'required|numeric|min:0.000000001|max:100'
            ,'status' => 'required'
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'start_date.required' => __('Start Date is required.'),
            'end_date.required' => __('End Date is required.'),
            'rate.required' => __('Rate is required.'),
            'rate.numeric' => __('Rate must be numeric.'),
        ];

        return $messages;
    }
}
