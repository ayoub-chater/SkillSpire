<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function demandes()
    {
        return $this->hasMany(Demande::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }
}
