<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
//use Illuminate\Database\Eloquent\Concerns\HasUuids;
//use App\Traits\UUID;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
        'password',
        'remember_token',
        'salt_key',
        'email_verified_at',
        'role_id',
        'city_id',
        'country_id'
    ];

    protected $with = [
        'role:id,uuid,slug,status',
        'country:id,uuid,name,code',
        'city:id,uuid,name,code,country_id'
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
        return $this->belongsTo(Country::class,'country_id','id');//->select('id','uuid','name','code');
    }

    public function city(){
        return $this->belongsTo(City::class);
    }
}
