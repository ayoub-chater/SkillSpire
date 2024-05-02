<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'centre_id',
        'admin_id',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function formations()
    {
        return $this->hasMany(Formation::class);
    }
}
