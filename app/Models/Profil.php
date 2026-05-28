<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    protected $table = 'profils';

    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'foto'
    ];

    public $timestamps = false;
}