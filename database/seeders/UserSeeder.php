<?php

namespace Database\Seeders;

use App\Models\UserModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = UserModel::create([
            'user_roles_id' => 1,
            'name' => 'Wahyu Agung',
            'email' => 'agung@landa.co.id',
            'password' => Hash::make('devGanteng'),
            'updated_security' => date('Y-m-d H:i:s')
        ]);
        

        DB::table('user_roles')->insert([
            'id' => 1,
            'name' => 'Admin',
            'access' => json_encode([
                'user' => [
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true
                ],
                'role' => [
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true
                ]
            ]),
        ]);
    }
}

