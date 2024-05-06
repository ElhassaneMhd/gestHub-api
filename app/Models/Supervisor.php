<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Supervisor extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

     // Par défaut, le modèle Encadrant hérite de tous les attributs et relations du modèle Utilisateur
    protected $fillable = [
        'profile_id', 
    ];

     public function profile(){
        return $this->belongsTo(Profile::class);
    }
    public function projects(){
        return $this->hasMany(Project::class);
    }

}
