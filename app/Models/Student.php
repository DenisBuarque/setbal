<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo','local','course_id','polo_id','name','rg','cpf','filiation','email','phone','cell','sexo','zip_code','address','district','city','state',
        'conclusion','date_entry','date_exit','year','registration','igreja','birth_date','birthplace','country','naturalness',
        'marital_status','login','password','active'];

    public function courses () {
        return $this->hasMany(Course::class);
    }

    public function polo () {
        return $this->belongsTo(Polo::class);
    }

    public function registrations () {
        return $this->hasMany(Registration::class);
    }

    public function inscricions() {
        return $this->hasMany(Inscription::class);
    }
}
