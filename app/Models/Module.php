<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['subject_id','title','slug','description','link','file','category'];

    public function subject () {
        return $this->belongsTo(Subject::class);
    }
}
