<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;
class Profile extends Authenticatable implements JWTSubject
{
    use  HasFactory, Notifiable, HasRoles;
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
    public function getJWTIdentifier() {
        return $this->getKey();
    }
    public function getJWTCustomClaims() {
        return [];
    }    
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
     protected static function boot()
    {
        parent::boot();
            static::deleting(function ($profile) {
                $profile->files()->delete();          
            });
    }
}
