<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplainType extends Model
{
    use HasFactory;

    protected $table = 'complain_type';
    protected $fillable = [
        'uuid',
        'type',
        'description',
        'status',
        'company_id'
    ];

    protected $hidden = ['id'];
//    protected $with = [
//        'company:id,uuid,name,email',
//    ];

    public function company(){
        return $this->belongsTo(Company::class,'company_id');
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strtolower($value),
            set: fn ($value) => strtolower($value),
        );
    }
    protected function type(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst($value),
            set: fn ($value) => strtolower($value),
        );
    }
    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst($value),
            set: fn ($value) => strtolower($value),
        );
    }

}
