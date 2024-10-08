<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_profile',
        'token',
        'status',
        'device',
        'ip'
    ];
    public function profile(){
        return $this->belongsTo(Profile::class);
    }
    public function activities(){
        return $this->hasMany(Activitie::class);
    }
    protected static function boot(){
        parent::boot();
            static::deleting(function ($session) {
                $session->activities()->delete();          
            });
    }
}
