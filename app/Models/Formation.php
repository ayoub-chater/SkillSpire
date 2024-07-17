<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'start_date',
        'end_date',
        'image_path',
        'professor_id',
        'centre_id',
        'salle_id',
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'inscriptions', 'formation_id', 'user_id');
    }
}
