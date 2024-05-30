<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    protected $fillable = [
        "motivationLetter",
        'offre_id',
        'user_id',
        "startDate",
        "endDate"
    ];   

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function intern(){
        return $this->belongsTo(Intern::class);
    }
    public function offer(){
        return $this->belongsTo(Offer::class);
    }
    public function files() {
 	    return $this->morphMany(File::class, 'fileable'); 
	}
    protected static function boot(){
    parent::boot();
        static::deleting(function ($profile) {
            $profile->files()->delete();
        });
    }
}


