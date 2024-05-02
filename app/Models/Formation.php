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
        'professeur_id',
        'center_id',
        'salle_id',
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function center()
    {
        return $this->belongsTo(Center::class);
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
}