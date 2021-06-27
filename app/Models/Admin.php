<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $fillable = ['email', 'phone_number', 'password', 'name', 'role', 'ib_id', 'admin_id', 'status', 'staff_commission', 'commission'];


    public function commission()
    {
        return $this->belongsTo('App\Models\AdminCommission', 'admin_id', 'id');
    }
}
