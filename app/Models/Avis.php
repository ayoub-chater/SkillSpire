<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_id',
        'formation_id',
        'comment',
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }
}
