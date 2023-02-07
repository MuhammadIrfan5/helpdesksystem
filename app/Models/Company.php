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
    use HasFactory,HasApiTokens;

    protected $table = "companies";

    protected $fillable = [
        'uuid',
        'user_created_by',
        'country_id',
        'city_id',
        'package_id',
        'role_id',
        'registration_no',
        'name',
        'email',
        'secondary_email',
        'phone_no',
        'logo',
        'mobile_no',
        'latitude',
        'longitude',
        'is_approved',
        'address',
        'password',
        'engineer_limit',
        'employee_limit',
        'company_key',
    ];

    protected $hidden = [
        'password'
    ];
}
