<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'uuid' => Str::uuid(),
            'name' => 'super admin',
            'slug' => 'super-admin',
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('roles')->insert([
            'uuid' => Str::uuid(),
            'name' => 'admin',
            'slug' => 'admin',
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('roles')->insert([
            'uuid' => Str::uuid(),
            'name' => 'company',
            'slug' => 'company',
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('roles')->insert([
            'uuid' => Str::uuid(),
            'name' => 'engineer',
            'slug' => 'engineer',
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('roles')->insert([
            'uuid' => Str::uuid(),
            'name' => 'employee',
            'slug' => 'employee',
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
