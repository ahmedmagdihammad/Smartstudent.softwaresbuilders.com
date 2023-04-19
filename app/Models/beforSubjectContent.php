<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class beforSubjectContent extends Model
{
        protected $primarykey='beforSubjectContentId';
        protected $fillable=['beforSubjectContentName','orderId','subSubjectId'];


    use HasFactory;
        public static function getBeforSubjectContent($ids)
    {
       return self::query()->select('beforSubjectContentId')->
        whereIn('beforSubjectContentId',$ids)->get();
     }
}
