<?php

namespace App\Http\Requests\Promo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class DiscountRequest extends FormRequest
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
            'promo_id' => 'Promo',
            'late_under_3' => 'Telat dibawah 3x',
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
            'tahsin' => 'numeric',
            'competency_matrix' => 'numeric',
            'late_under_3' => 'numeric',
            'full_absensi' => 'numeric',
        ];
    }

    private function updateRules(): array
    {
        return [
            'id' => 'required|numeric',
            'customer_id' => 'required|numeric',
            'promo_id' => 'required|numeric',
            'tahsin' => 'numeric',
            'competency_matrix' => 'numeric',
            'late_under_3' => 'numeric',
            'full_absensi' => 'numeric',
        ];
    }
}
