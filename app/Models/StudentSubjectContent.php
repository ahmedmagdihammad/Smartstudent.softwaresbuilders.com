<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubjectContent extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable=['studentId','subjectContentId','beforSubjectContentId'];
    protected $primaryKey = 'studentSubjectContentId';


    public function studentId()
    {
        return $this->belongsTo(Student::class,'studentId');
    }

    public function subjectContentId()
    {
        return $this->belongsTo(subjectContent::class,'subjectId');
    }
}
