<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

use Illuminate\Foundation\Auth\User as Authenticatable;


class student extends Authenticatable
{
    use HasFactory,HasApiTokens;
    use Notifiable;
    
    protected $fillable=['studentName','educationId','email','password','location','gender','phone','code'];
    protected $primaryKey = 'studentId';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
  
    public function educationLevels()
    {
        return $this->belongsTo(EducationLevel::class,'education_id');
    }


}


