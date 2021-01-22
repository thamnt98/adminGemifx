<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
        'user_id',
        'amount_money',
        'status',
        'order_number',
        'bank_name',
        'sign',
        'created_at',
        'updated_at',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
