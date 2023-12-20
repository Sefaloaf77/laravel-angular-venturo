<?php

namespace Database\Seeders;

use App\Models\RoleModel;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = RoleModel::create([
            'name' => 'super admin',
            'access' => 'All Access'
        ]);
    }
}
