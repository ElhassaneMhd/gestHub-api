<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = ['model','isRead','object','action','activity','initiator','receiver'];
    public function initiator(){
        return $this->belongsTo(Profile::class, 'initiator');
    }
    public function receiver(){
        return $this->belongsTo(Profile::class, 'receiver');
    }
}
