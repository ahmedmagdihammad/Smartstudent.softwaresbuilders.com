<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class subjectContent extends Model
{
    use HasFactory;
    protected $primaryKey = 'subjectContentId';
   
   protected $fillable=['subjectContentName','subjectContentimage','video_url','price','description','teacherId','beforSubjectContentId','SubSubjectId','subjectId'];
   
   public static function getSubjectContent($ids)
   {
      return self::query()->select('SubjectContentId')->
      whereIn('SubjectContentId',$ids)->get();
   }
   
   public function getPdfSubjectContents()
   {
      return $this->hasMany(PdfSubjectContent::class, 'subjectContentId');
   }

}
