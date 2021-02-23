<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpViaMail;
use App\Repositories\AdminRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{

    protected $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function main(Request $request)
    {
        $data = $request->all();
        $validateData = $this->validateData($data);
        if ($validateData->fails()) {
            return redirect()->back()->withErrors($validateData->errors())->withInput();
        }
        $data['password'] = Hash::make($data['password']);
        try {
            DB::beginTransaction();
            $this->adminRepository->changePassword($data);
            DB::commit();
            return redirect('/login')->with('success', 'You changed password successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function validateData($data)
    {
        return Validator::make(
            $data,
            [
                'email' => 'required|email|exists:admins',
                'password' => 'required|regex:/[A-z0-9]{8,}/',
                'password_confirmation' => 'required|same:password',
            ]
        );
    }
}
