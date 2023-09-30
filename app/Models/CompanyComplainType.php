<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyComplainType extends Model
{
    use HasFactory;

    protected $table = 'company_complain_type';

    protected $hidden = ['id'];

    protected $fillable = [
        'uuid',
        'company_id',
        'complain_type_id',
        'employee_id',
        'employee_role_id',
    ];
    protected $casts = [
        'company_id' => 'string',
        'complain_type_id' => 'string',
        'employee_id' => 'string',
        'employee_role_id' => 'string',
    ];

}
