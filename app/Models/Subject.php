<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['type','course_id','title','slug','year','semester','workload','period','credits','description','quiz','status'];

    public function user() {
        return $this->hasMany(User::class);
    }

    public function course () {
        return $this->belongsTo(Course::class);
    }

    public function modules () 
    {
        return $this->hasMany(Module::class);
    }

    public function inscriptions () {
        return $this->hasMany(Inscription::class);
    }

    public function murals () {
        return $this->hasMany(Mural::class);
    }

    public function forums () {
        return $this->hasMany(Forum::class);
    }

    public function open_question () {
        return $this->hasMany(OpenQuestion::class);
    }

    public function multiple_response ()
    {
        return $this->hasMany(MultipleResponse::class);
    }

    public function open_response ()
    {
        return $this->hasMany(OpenResponse::class);
    }
}
