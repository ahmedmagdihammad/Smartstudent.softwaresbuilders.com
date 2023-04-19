<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PdfSubjectContent extends Model
{
    use HasFactory;

    protected $table = 'pdf_subject_contents';

    protected $fillable=['subjectContentId','pdfs'];

    public function getSubjectContents()
    {
        return $this->belongsTo(subjectContent::class, 'subjectContentId');
    }

}
