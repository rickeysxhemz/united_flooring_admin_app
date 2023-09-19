<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Comment extends Model
{
    use HasFactory;
    public function sender()
    {
        return $this->belongsTo(User::class,'sender_id','id')->with('roles');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class,'receiver_id','id')->with('roles');
    }
}
