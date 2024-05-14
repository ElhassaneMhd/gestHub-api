<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
class Profile extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    protected $fillable = [ 
        'firstName',
        'lastName',
        'phone',
        'email',
        'password',
        'role',
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
    
    public function intern()
    {
        return $this->hasOne(Intern::class);
    }
    public function user()
    {
        return $this->hasOne(User::class);
    }
    public function supervisor()
    {
        return $this->hasOne(Supervisor::class);
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
