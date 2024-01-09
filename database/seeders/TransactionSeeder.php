<?php

namespace Database\Seeders;

use App\Models\SalesModel;
use Illuminate\Database\Seeder;
use App\Models\SalesDetailModel;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SalesModel::factory()
            ->count(500)
            ->create()
            ->each(function ($sales) {
                SalesDetailModel::factory()->count(2)->create([
                    't_sales_id' => $sales->id
                ]);
            });
    }
}
