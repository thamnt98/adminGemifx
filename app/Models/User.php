<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'password',
        'phone_number',
        'application_type',
        'country',
        'state',
        'address',
        'zip_code',
        'city',
        'copy_of_id',
        'proof_of_address',
        'addtional_file'
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . '_' . $this->last_name;
    }
}
