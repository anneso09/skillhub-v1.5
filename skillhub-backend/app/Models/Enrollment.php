<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'utilisateur_id',
        'formation_id',
        'progression',
    ];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
}