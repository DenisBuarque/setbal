<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = ['photo','name','phone','email','description','login','password','status','course_id','subject_id'];

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function course () {
        return $this->belongsTo(Course::class);
    }
}
