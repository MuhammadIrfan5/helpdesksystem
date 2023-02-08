<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $hidden = [
        'id'
    ];

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'status'
    ];

}
