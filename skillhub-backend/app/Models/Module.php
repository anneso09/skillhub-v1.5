<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'titre',
        'contenu',
        'formation_id',
        'ordre',
    ];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }
}