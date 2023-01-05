<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'punctuation',
        'course_id',
        'subject_id'
    ];

    public function course () {
        return $this->belongsTo(Course::class);
    }

    public function subject () {
        return $this->belongsTo(Subject::class);
    }
}
