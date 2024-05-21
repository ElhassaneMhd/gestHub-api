<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activitie extends Model
{
    use HasFactory;
    protected $fillable = [
        'action',
        'model',
        'activity',
        'object',
        'session_id',
        'profile_id',
    ];
    public function session(){
        return $this->belongsTo(Session::class);
    }
    public function profile(){
        return $this->belongsTo(Profile::class);
    }
}
