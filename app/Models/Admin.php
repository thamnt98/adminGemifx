<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $fillable = ['email', 'phone_number', 'password', 'name', 'role', 'ib_id'];
}
