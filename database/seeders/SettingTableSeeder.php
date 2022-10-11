<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'company_name' => 'SR12',
            'address' => 'Gebang',
            'phone_number' => '+6289304336',
            'type_nota' => 1, // kecil
            'discount' => 5,
            'path_logo' => '/images/LOGOZN1.png',
            'path_member_card' => '/images/id_card.png'
        ]);
    }
}