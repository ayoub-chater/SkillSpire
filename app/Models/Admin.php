<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function centers()
    {
        return $this->hasMany(Center::class);
    }

    public function salles()
    {
        return $this->hasMany(Salle::class);
    }
}
