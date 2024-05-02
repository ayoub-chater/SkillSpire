<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'expertise',
        'qualification'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function formations()
    {
        return $this->hasMany(Formation::class);
    }
}