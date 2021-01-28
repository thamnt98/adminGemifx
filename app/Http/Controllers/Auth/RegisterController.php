<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    public function main()
    {
        Session::forget('email');
        Session::forget('otp');
        Session::forget('otp_valid');
        return view('auth.email');
    }
}
