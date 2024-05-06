<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject',
        'description',
        "startDate","endDate",
        'status',
        'priority',
        'supervisor_id', 
        "intern_id"
    ];

    public function supervisor(){
        return $this->belongsTo(Supervisor::class);
    }
    public function projectManager(){
        return $this->belongsTo(Intern::class,'intern_id');
    }
    public function interns(){
        return $this->belongsToMany(Intern::class);
    }
    public function tasks(){
        return $this->hasMany(task::class);
    }
}
