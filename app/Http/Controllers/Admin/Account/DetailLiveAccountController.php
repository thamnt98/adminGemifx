<?php

namespace App\Http\Controllers\Admin\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DetailLiveAccountController extends Controller
{
    public function main()
    {
        return view('admin.account.detaillive');
    }
}
