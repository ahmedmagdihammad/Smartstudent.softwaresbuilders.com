<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class father extends Model
{
        protected $fillable=['fatherId','fatherName','email','password','location','gender','phone','code'];
    use HasFactory;
}
