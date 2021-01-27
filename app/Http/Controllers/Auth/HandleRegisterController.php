<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\AdminRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        $validateData = $this->validateData($data);
        if ($validateData->fails()) {
            return redirect()->back()->withErrors($validateData->errors())->withInput();
        }
        $data['password'] = Hash::make($data['password']);
        $data['ib_id'] = rand(100000, 999999);
        $admin = $this->adminRepository->create($data);
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
                'name' => 'required|max:255',
                'email' => 'required|email|unique:admins',
                'password' => 'required|regex:/[A-z0-9]{8,}/',
                'password_confirmation' => 'required|same:password',
                'phone_number' => 'required|regex:/[0-9]{10,11}/',
            ]
        );
    }
}
