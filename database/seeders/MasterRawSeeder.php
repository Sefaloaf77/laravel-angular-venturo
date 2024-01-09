<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterRawSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = file_get_contents(database_path('sql/MasterRawSeeder.sql'));

        DB::unprepared($sql);

        $this->command->info('MasterRawSeeder.sql seeded successfully.');
    }
}
