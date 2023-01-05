<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'image',
        'nivel',
        'local',
        'name',
        'phone',
        'zip_code',
        'address',
        'number',
        'district',
        'city',
        'state',
        'email',
        'password',
        'rg',
        'cpf',
        'filiation',
        'sexo',
        'conclusion',
        'date_entry',
        'date_exit',
        'year',
        'registration',
        'igreja',
        'birth_date',
        'birthplace',
        'country',
        'naturalness',
        'marital_status',
        'active',
        'polo_id',
        'course_id',
        'subject_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function permissions ()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasPermissions ($value)
    {
        $userPermission = $this->permissions;
        return $value->intersect($userPermission)->count();
    }

    public function forum () {
        return $this->belongsTo(Forum::class);
    }

    public function opinions () {
        return $this->hasMany(Opinion::class);
    }

    public function tickets () {
        return $this->hasMany(Ticket::class);
    }

    public function multiple_response ()
    {
        return $this->hasMany(MultipleResponse::class);
    }

    public function open_response ()
    {
        return $this->hasMany(OpenResponse::class);
    }

    public function course ()
    {
        return $this->belongsTo(Course::class);
    }

    public function subject ()
    {
        return $this->belongsTo(Subject::class);
    }

    public function registrations ()
    {
        return $this->belongsTo(Registration::class);
    }

    public function polo ()
    {
        return $this->belongsTo(Polo::class);
    }
}
