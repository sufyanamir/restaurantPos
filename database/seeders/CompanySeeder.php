<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('company')->insert([
            'company_name' => 'Company',
            'company_email' => 'company@gmail.com',
            'company_phone' => '0123456789',
            'company_address' => 'abcdefghikjlm',
        ]);
    }
}
