<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'status'
    ];

    protected $hidden = [
        'password',
        'id',
        'country_id',
        'city_id',
        'package_id',
        'role_id',
    ];

    protected $with = [
        'role:id,uuid,slug,status',
        'country:id,uuid,name,code',
        'city:id,uuid,name,code,country_id',
        'package:id,uuid,name,package_cost',
        'created_by:id,uuid,first_name,last_name,email,role_id,city_id,country_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(){
        return $this->belongsTo(Role::class,'role_id','id');//->select('uuid','slug');
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function package(){
        return $this->belongsTo(Package::class);
    }

    public function created_by(){
        return $this->belongsTo(User::class,'user_created_by','id');
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst($value),
            set: fn ($value) => strtolower($value),
        );
    }
    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst($value),
            set: fn ($value) => strtolower($value),
        );
    }
    protected function secondaryEmail(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst($value),
            set: fn ($value) => strtolower($value),
        );
    }
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strtolower($value),
            set: fn ($value) => strtolower($value),
        );
    }
}
