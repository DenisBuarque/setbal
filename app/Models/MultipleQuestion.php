<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultipleQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'response_one',
        'response_two',
        'response_tree',
        'response_four',
        'gabarito',
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

    public function multiple_response ()
    {
        return $this->hasMany(MultipleResponse::class);
    }
}
