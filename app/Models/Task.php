<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'dueDate',
        'priority',
        'status',
        'intern_id',
        'project_id',
    ];

    public function intern(){
        return $this->belongsTo(Intern::class);
    }

    public function project(){
        return $this->belongsTo(Project::class);
    }


}
