<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class educationLevel extends Model
{
    use HasFactory;
    protected $fillable=['nameAr','nameEn'];
    public static function getEducatinsIds($ids)
    {
       return self::query()->select('educationId')->
        whereIn('educationId',$ids)->get();
     }


}
