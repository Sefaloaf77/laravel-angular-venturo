<?php

namespace Database\Factories;

use App\Models\CustomerModel;
use App\Models\VoucherModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalesModelFactory extends Factory
{
    public function getCustomersId()
    {
        $model = new CustomerModel();
        $customers = $model->get();

        return array_map(function ($customer) {
            return $customer['id'];
        }, $customers->toArray());
    }

    public function getVouchersId()
    {
        $model = new VoucherModel();
        $vouchers = $model->get();

        return array_map(function ($voucher) {
            return $voucher['id'];
        }, $vouchers->toArray());
    }
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $customers = $this->getCustomersId();
        $vouchers = $this->getVouchersId();

        return [
            'm_customer_id' => $this->faker->randomElement($customers),
            'm_voucher_id' => $this->faker->randomElement($vouchers),
            'voucher_nominal' => $this->faker->numberBetween(2000, 10000),
            'date' => $this->faker->dateTimeBetween('-300 days', '+200 days')
        ];
    }
}
