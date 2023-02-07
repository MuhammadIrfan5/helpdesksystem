<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PackagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packages')->insert([
            'uuid' => Str::uuid(),
            'name' => 'silver',
            'slug' => 'silver',
            'branch_limit' => '2',
            'employee_limit' => '80',
            'engineer_limit' => '3',
            'package_cost' => '25000',
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('packages')->insert([
            'uuid' => Str::uuid(),
            'name' => 'gold',
            'slug' => 'gold',
            'branch_limit' => '5',
            'employee_limit' => '400',
            'engineer_limit' => '6',
            'package_cost' => '45000',
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('packages')->insert([
            'uuid' => Str::uuid(),
            'name' => 'platinum',
            'slug' => 'platinum',
            'branch_limit' => '10',
            'employee_limit' => '1000',
            'engineer_limit' => '10',
            'package_cost' => '60000',
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
