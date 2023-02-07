<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use mysql_xdevapi\Table;

class Employee extends Authenticatable
{
    use HasFactory;
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
        'password'
    ];
}
