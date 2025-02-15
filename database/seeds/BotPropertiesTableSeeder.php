<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BotPropertiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents('database/seeds/sql/bot_properties.sql'));
    }
}
