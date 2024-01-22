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
            'customer_id' => 'required',
            'voucher_id' => 'nullable',
            'voucher_nominal' => 'nullable',
            'discount_id' => 'nullable',
            'date' => 'nullable',
            'details.*.total_item' => 'numeric',
            'details.*.price' => 'required|numeric',
            'details.*.discount_nominal' => 'numeric',
            'details.*.product_id' => 'required|numeric',
            'details.*.product_detail_id' => 'nullable',
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
