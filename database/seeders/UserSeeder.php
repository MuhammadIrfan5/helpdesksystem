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
            'id' => Str::uuid(),
            'first_name' => "Muhammad",
            'last_name' => "Irfan",
            'phone' => "03362377443",
            'address' => "16-c south park avenue DHA Phase 2 ext karachi 75500",
            'email' => "irfan@prismalab.com.pk",
            'password' => Hash::make('password'),
            'salt_key' => Str::random(10),
            'role_id' => "bc910d9d-a224-11ed-80c0-b4b686ebc1ac",
            'country_id' => 170,
            'city_id' => "f873dbd0-a232-11ed-80c0-b4b686ebc1ac",
            'created_at' => Carbon::now(),
        ]);
        DB::table('users')->insert([
            'id' => Str::uuid(),
            'first_name' => "Jibran",
            'last_name' => "Masood",
            'phone' => "03452011407",
            'address' => "16-c south park avenue DHA Phase 2 ext karachi 75500",
            'email' => "jibran@solutionvennd.com.pk",
            'password' => Hash::make('jibran123$'),
            'salt_key' => Str::random(10),
            'role_id' => "bc910d9d-a224-11ed-80c0-b4b686ebc1ac",
            'country_id' => 170,
            'city_id' => "f873dbd0-a232-11ed-80c0-b4b686ebc1ac",
            'created_at' => Carbon::now(),
        ]);
        DB::table('users')->insert([
            'id' => Str::uuid(),
            'first_name' => "Muhammad",
            'last_name' => "Bilal",
            'phone' => "03432984735",
            'address' => "16-c south park avenue DHA Phase 2 ext karachi 75500",
            'email' => "bilal@prismalab.com.pk",
            'password' => Hash::make('bilal123$'),
            'salt_key' => Str::random(10),
            'role_id' => "bc910d9d-a224-11ed-80c0-b4b686ebc1ac",
            'country_id' => 170,
            'city_id' => "f873dbd0-a232-11ed-80c0-b4b686ebc1ac",
            'created_at' => Carbon::now(),
        ]);
        DB::table('users')->insert([
            'id' => Str::uuid(),
            'first_name' => "Admin",
            'last_name' => "User",
            'phone' => "03182377443",
            'address' => "16-c south park avenue DHA Phase 2 ext karachi 75500",
            'email' => "admin@prismalab.com.pk",
            'password' => Hash::make('bilal123$'),
            'salt_key' => Str::random(10),
            'role_id' => "bc912df1-a224-11ed-80c0-b4b686ebc1ac",
            'country_id' => 170,
            'city_id' => "f873dbd0-a232-11ed-80c0-b4b686ebc1ac",
            'created_at' => Carbon::now(),
        ]);
    }
}
