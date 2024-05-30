<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class Profile extends Authenticatable 
{
    use  HasFactory, Notifiable, HasRoles,HasApiTokens;
    protected $fillable = [ 
        'firstName',
        'lastName',
        'phone',
        'email',
        'password',
        "gender"
        ];
        protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];  
      public function admin(){
        return $this->hasOne(Admin::class);
    }
    public function intern(){
        return $this->hasOne(Intern::class);
    }
    public function user(){
        return $this->hasOne(User::class);
    }
    public function supervisor(){
        return $this->hasOne(Supervisor::class);
    }
    public function sessions(){
        return $this->hasMany(Session::class);
    }
    public function activities(){
        return $this->hasMany(Activitie::class);
    }
    public function files() {
 	    return $this->morphMany(File::class, 'fileable'); 
	}
    public function notifications(){
        return $this->hasMany(Notification::class, 'receiver');
    }
     protected static function boot()
    {
        parent::boot();
            static::deleting(function ($profile) {
                $profile->files()->delete();          
            });
    }
}
