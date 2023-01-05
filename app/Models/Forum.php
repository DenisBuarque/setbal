<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasFactory;

    protected $fillable = ['title','description','course_id','subject_id'];

    public function course () {
        return $this->belongsTo(Course::class);
    }

    public function subject () {
        return $this->belongsTo(Subject::class);
    }

    public function users () {
        return $this->hasMany(User::class);
    }

    public function comments () {
        return $this->hasMany(Comment::class);
    }
}
