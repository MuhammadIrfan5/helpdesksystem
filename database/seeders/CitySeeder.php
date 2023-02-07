<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cities')->insert([
        'uuid' => Str::uuid(),
        'country_id' => 1,
        'name' => 'karachi',
        'code' => 'khi',
        'status' => 'active',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
        ]);
        DB::table('cities')->insert([
            'uuid' => Str::uuid(),
            'country_id' => 1,
            'name' => 'lahore',
            'code' => 'lhr',
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('cities')->insert([
            'uuid' => Str::uuid(),
            'country_id' => 1,
            'name' => 'islamabad',
            'code' => 'isl',
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('cities')->insert([
            'uuid' => Str::uuid(),
            'country_id' => 1,
            'name' => 'peshawar',
            'code' => 'psh',
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('cities')->insert([
            'uuid' => Str::uuid(),
            'country_id' => 1,
            'name' => 'hyderabad',
            'code' => 'hyd',
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
