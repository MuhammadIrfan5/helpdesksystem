<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employee_type')->insert([
            'uuid' => Str::uuid(),
            'type' => 'permanent',
            'slug' => 'permanent',
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('employee_type')->insert([
            'uuid' => Str::uuid(),
            'type' => 'probationary',
            'slug' => 'probationary',
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('employee_type')->insert([
            'uuid' => Str::uuid(),
            'type' => 'contractual',
            'slug' => 'contractual',
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('employee_type')->insert([
            'uuid' => Str::uuid(),
            'type' => 'others',
            'slug' => 'others',
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
