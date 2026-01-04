<?php

namespace App\Models;

use App\Models\Post;
use App\Models\User;
use App\Models\TimeSlot;
use Illuminate\Database\Eloquent\Model;

class PinterestAccount extends Model
{
    protected $guarded = []; // Mass assignment allow karne ke liye

public function user()
{
    return $this->belongsTo(User::class);
}

public function posts()
{
    return $this->hasMany(Post::class);
}

public function timeSlots()
{
    return $this->hasMany(TimeSlot::class);
}
}
