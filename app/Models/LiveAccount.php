<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LiveAccount extends Model
{
    protected $fillable = [
        'user_id',
        'group',
        'leverage',
        'login',
        'phone_number',
    ];

    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
