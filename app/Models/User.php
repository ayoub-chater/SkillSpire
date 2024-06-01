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
        'name',
        'email',
        'password',
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
        'password' => 'hashed',
    ];

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    public function professors()
    {
        return $this->hasMany(Professor::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }









    public function centres()
    {
        return $this->hasMany(Centre::class);
    }

    public function salles()
    {
        return $this->hasMany(Salle::class);
    }

    public function formations()
    {
        return $this->hasMany(Formation::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }

    public function demands()
    {
        return $this->hasMany(Demande::class);
    }

}
