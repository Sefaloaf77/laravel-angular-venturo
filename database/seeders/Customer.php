<?php

namespace Database\Seeders;

use App\Models\CustomerModel;
use Illuminate\Database\Seeder;

class Customer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomerModel::factory()->count(25)->create();
    }
}
