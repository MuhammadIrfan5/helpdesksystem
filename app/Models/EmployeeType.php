<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeType extends Model
{
    use HasFactory;
    protected $hidden = [
        'id'
    ];
    protected $table = 'employee_type';

    protected $fillable = [
        'uuid',
        'type',
        'slug',
        'status'
    ];
}
