<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Intern extends Authenticatable
{

    use HasFactory, Notifiable;

    protected $fillable = [
        'project_id',
        'profile_id',
        'projectLink',
        'academicLevel',
        'establishment',
        "startDate",
        'endDate',
        'specialty'
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

