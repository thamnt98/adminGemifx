<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpViaMail;
use App\Repositories\AdminRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Session;

class HandleRegisterController extends Controller
{

    protected $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function main(Request $request)
    {
        $data = $request->all();
        if (!Session::has('email')) {
            $validateEmail = $this->validateEmail($data['email']);
            if ($validateEmail->fails()) {
                return redirect()->back()->withErrors($validateEmail->errors())->withInput();
            }
            $otp = rand(100000, 999999);
            Mail::to($data['email'])->send(new SendOtpViaMail($otp));
            Session::put('email', $data['email']);
            Session::put('otp', $otp);
            return view('auth.otp');
        }
        if (isset($data['otp'])) {
            if (Session::get('otp') != $data['otp']) {
                Session::put('otp_valid', 'OTP is invalid');
                return view('auth.otp');
            }
            Session::forget('otp_valid');
            return view('auth.register');
        }
        $validateData = $this->validateData($data);
        if ($validateData->fails()) {
            return redirect()->back()->withErrors($validateData->errors())->withInput();
        }
        $data['password'] = Hash::make($data['password']);
        $data['ib_id'] = rand(100000, 999999);
        $admin = $this->adminRepository->create($data);
        Session::forget('email');
        Session::forget('otp');
        Session::forget('otp_valid');
        if ($admin) {
            return redirect()->route('login')->with('success', 'You registered successfully');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function validateData($data)
    {
        return Validator::make(
            $data,
            [
                'name'                  => 'required|max:255',
                'password'              => 'required|regex:/[A-z0-9]{8,}/',
                'password_confirmation' => 'required|same:password',
                'phone_number'          => 'required|regex:/[0-9]{10,11}/',
            ]
        );
    }

    public function validateEmail($email)
    {
        return Validator::make(
            ['email' => $email],
            [
                'email' => 'required|email|unique:admins',
            ]
        );
    }
}
