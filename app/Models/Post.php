<?php

namespace App\Models;

use App\Models\PinterestAccount;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];
protected $casts = [
    'scheduled_at' => 'datetime',
];

public function account()
{
    return $this->belongsTo(PinterestAccount::class, 'pinterest_account_id');
}
}
