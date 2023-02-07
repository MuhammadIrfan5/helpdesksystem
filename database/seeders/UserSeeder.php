<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'uuid' => Str::uuid(),
            'first_name' => "Muhammad",
            'last_name' => "Irfan",
            'phone' => "03362377443",
            'address' => "16-c south park avenue DHA Phase 2 ext karachi 75500",
            'email' => "irfan@prismalab.com.pk",
            'password' => Hash::make('password'),
            'salt_key' => Str::random(10),
            'role_id' => "1",
            'country_id' => 1,
            'city_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('users')->insert([
            'uuid' => Str::uuid(),
            'first_name' => "Jibran",
            'last_name' => "Masood",
            'phone' => "03452011407",
            'address' => "16-c south park avenue DHA Phase 2 ext karachi 75500",
            'email' => "jibran@solutionvennd.com.pk",
            'password' => Hash::make('jibran123$'),
            'salt_key' => Str::random(10),
            'role_id' => "1",
            'country_id' => 1,
            'city_id' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('users')->insert([
            'uuid' => Str::uuid(),
            'first_name' => "Muhammad",
            'last_name' => "Bilal",
            'phone' => "03432984735",
            'address' => "1",
            'email' => "bilal@prismalab.com.pk",
            'password' => Hash::make('bilal123$'),
            'salt_key' => Str::random(10),
            'role_id' => "2",
            'country_id' => 1,
            'city_id' => 4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('users')->insert([
            'uuid' => Str::uuid(),
            'first_name' => "Admin",
            'last_name' => "User",
            'phone' => "03182377443",
            'address' => "16-c south park avenue DHA Phase 2 ext karachi 75500",
            'email' => "admin@prismalab.com.pk",
            'password' => Hash::make('bilal123$'),
            'salt_key' => Str::random(10),
            'role_id' => "2",
            'country_id' => 1,
            'city_id' => 5,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
