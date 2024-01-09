<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class SalesRequest extends FormRequest
{
    public $validator;

    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod('post')) {
            return $this->createRules();
        }

        return $this->updateRules();
    }

    private function createRules(): array
    {
        return [
            'm_customer_id' => 'required',
            'm_voucher_id' => 'nullable',
            'voucher_nominal' => 'nullable',
            'm_discount_id' => 'nullable',
            'date' => 'nullable',
            'details.*.total_price' => 'required',
            'details.*.price' => 'required|numeric',
            'details.*.discount_nominal' => 'numeric',
            'details.*.m_product_id' => 'numeric',
        ];
    }

    private function updateRules(): array
    {
        return [
            'id' => 'reuqired',
            'm_customer_id' => 'required',
            'm_voucher_id' => 'nullable',
            'voucher_nominal' => 'nullable',
            'm_discount_id' => 'nullable',
            'date' => 'nullable',
        ];
    }
}
