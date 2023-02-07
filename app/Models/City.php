<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class City extends Model
{
    use HasFactory;

    protected $hidden = [
        'id',
        'country_id'
    ];

    protected $with= [
        'country:id,uuid,name,code'
    ];

    public function country(){
        return $this->belongsTo(Country::class);
    }

}
