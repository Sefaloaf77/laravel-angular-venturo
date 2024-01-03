<?php

namespace App\Http\Requests\Promo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class VoucherRequest extends FormRequest
{
    public $validator;

    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }


    public function attributes()
    {
        return [
            'customer_id' => 'Customer',
            'promo_id' => 'Voucher',
            'nominal_rupiah' => 'Nominal',
        ];
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
            'customer_id' => 'required|numeric',
            'promo_id' => 'required|numeric',
            'start_time' => 'required',
            'end_time' => 'required',
            'total_voucher' => 'required|numeric',
            'nominal_rupiah' => 'required|numeric',
        ];
    }


    private function updateRules(): array
    {
        return [
            'id' => 'required|numeric',
            'customer_id' => 'required|numeric',
            'promo_id' => 'required|numeric',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'total_voucher' => 'nullable|numeric',
            'nominal_rupiah' => 'nullable|numeric',
        ];
    }
}
