<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Admin extends Authenticatable

{
    use HasFactory, Notifiable;

    protected $fillable = [
        'profile_id',    
    ];
    public function profile(){
        return $this->belongsTo(Profile::class);
    }
}
