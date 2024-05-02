<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'image_path',
        'admin_id',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function salles()
    {
        return $this->hasMany(Salle::class);
    }

    public function formations()
    {
        return $this->hasMany(Formation::class);
    }
}
