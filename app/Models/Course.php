<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['title','polo_id','slug','duration','description','type','status','photo'];

    public function user() {
        return $this->hasMany(User::class);
    }

    public function subjects () {
        return $this->hasMany(Subject::class);
    }

    public function polo () {
        return $this->belongsTo(Polo::class);
    }

    public function teacher () {
        return $this->hasMany(Teacher::class);
    }

    public function registrations () {
        return $this->hasMany(Registration::class);
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
