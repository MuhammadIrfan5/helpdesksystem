<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Authenticatable
{
    use HasFactory,HasApiTokens, HasFactory;
    protected $table = 'employee';
    protected $fillable = [
        'uuid',
        'company_id',
        'branch_id' ,
        'country_id',
        'city_id' ,
        'role_id' ,
        'employee_code',
        'employee_type_id',
        'first_name' ,
        'company_email',
        'primary_phone_no',
        'status',
        'password'
    ];
    protected $hidden = [
        'password',
        'id',
        'country_id',
        'city_id',
        'branch_id',
        'role_id',
        'company_id',
        'employee_type_id'
    ];

    protected $with = [
        'role:id,uuid,slug',
        'country:id,uuid,name,code',
        'city:id,uuid,name,code,country_id',
        'company:id,uuid,name,email,package_id,country_id,city_id,role_id,user_created_by,status,is_approved',
        'employee_type:id,uuid,type'
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

    public function company(){
        return $this->belongsTo(Company::class,'company_id','id');
    }

    public function employee_type(){
        return $this->belongsTo(EmployeeType::class);
    }

    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst($value),
            set: fn ($value) => strtolower($value),
        );
    }
    protected function lastName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst($value),
            set: fn ($value) => strtolower($value),
        );
    }
    protected function companyEmail(): Attribute
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
