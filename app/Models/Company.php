<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\Uuids;
use Laravel\Sanctum\HasApiTokens;

class Company extends Authenticatable
{
    use HasFactory,HasApiTokens,HasUuids;

    protected $table = "companies";

    protected $fillable = [
        'user_created_by',
        'country_id',
        'city_id',
        'package_id',
        'registration_no',
        'name',
        'email',
        'phone_no',
        'logo',
        'mobile_no',
        'latitude',
        'longitude',
        'is_approved',
        'address',
        'password',
    ];

    protected $hidden = [
        'password'
    ];
}
