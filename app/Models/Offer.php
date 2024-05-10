<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'sector',
        'experience',
        'skills',
        'direction',
        'duration',
        'type',
        'visibility',
        'status',
        'city',
    ];
    
    public function applications(){
        return $this->hasMany(Application::class);
    }

}
