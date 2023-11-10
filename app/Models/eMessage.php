<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class eMessage extends Model
{
    use HasFactory;
    protected $table = 'e-messages';
    protected $guarded = [];

    
    public function eMessageData(){
        return $this->belongsTo(User::class,'sender_id','id');

    }
}
