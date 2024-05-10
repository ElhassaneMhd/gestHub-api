<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Intern extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'project_id',
        'profile_id',
        'projectLink',
        'academicLevel',
        'establishment',
        "startDate",
        'endDate',
        "gender"
    ];

      public function profile(){
        return $this->belongsTo(Profile::class);
    }
       public function applications(){
        return $this->hasMany(Application::class);
    }
      public function projects(){
        return $this->belongsToMany(Project::class);
    }
    public function managedBy(){
        return $this->belongsTo(Project::class);
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

}

