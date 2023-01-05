<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;

    protected $fillable = ['date_inscription','closing_date','course_id','subject_id','user_id','status'];

    public function user () {
        return $this->belongsTo(User::class);
    }

    public function course () {
        return $this->belongsTo(Course::class);
    }

    public function subject () {
        return $this->belongsTo(Subject::class);
    }
}
