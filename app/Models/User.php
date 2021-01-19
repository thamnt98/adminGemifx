<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{

    use SoftDeletes;

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
        return $this->first_name . ' ' . $this->last_name;
    }
}
