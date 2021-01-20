<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * ListController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function main($id, Request $request)
    {
        $data = $request->except('_token');
        $validateData = $this->validateData($data);
        if ($validateData->fails()) {
            return redirect()->back()->withErrors($validateData->errors())->withInput();
        }
        try {
            DB::beginTransaction();
            $this->userRepository->update($id, $data);
            // if ($result) {
            //     return redirect()->back()->with('error', $result);
            // }
            DB::commit();
            return redirect()->back()->with('success', 'Bạn đã cập nhật tài khoản thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Cập nhật thất bại');
        }   
    }

    public function validateData($data)
    {
        $countries = config('country');
        return Validator::make(
            $data,
            [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'country' => ['required', Rule::in(array_keys($countries))],
                'phone_number' => 'required|regex:/[0-9]{10,11}/',
                "city" => 'string|max:255',
                "state" => 'string|max:255',
                "zip_code" => 'string|max:255',
                "address" => 'string|max:255',
                'copy_of_id' => 'bail|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'addtional_file' => 'bail|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'proof_of_address' => 'bail|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]
        );
    }
}
