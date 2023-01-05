<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenResponse extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','course_id','subject_id','open_question_id','comments','nota'];

    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    public function course ()
    {
        return $this->belongsTo(Course::class);
    }

    public function subject ()
    {
        return $this->belongsTo(Subject::class);
    }

    public function open_question ()
    {
        return $this->belongsTo(OpenQuestion::class);
    }
}
