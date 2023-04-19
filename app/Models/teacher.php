<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class teacher extends Authenticatable
{
    use HasFactory,HasApiTokens;
    protected $fillable=['teacherName','education_level','subject'];
    protected $primarykey='teacherId';
    public static function getTeacherIds($ids)
    {
       return self::query()->select('teacherId')->
        whereIn('teacherId',$ids)->get();
     }
}
