<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalFund extends Model
{
    protected $fillable = [
        'user_id',
        'login',
        'currency',
        'available_balance',
        'withdrawal_type',
        'bank_account',
        'bank_name',
        'swift_code',
        'iban',
        'account_name',
        'balance',
        'withdrawal_currency',
        'amount',
        'account_holder',
        'bank_branch_name',
        'bank_address',
        'note',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
