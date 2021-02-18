<?php

namespace App\Http\Controllers\Admin\Agent;

use App\Http\Controllers\Controller;
use App\Repositories\AdminRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateController extends Controller
{
    /**
     * @var AdminRepository
     */
    private $adminRepository;

    /**
     * LiveListController constructor.
     * @param \App\Repositories\AdminRepository $adminRepository
     */
    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function main($id, Request $request)
    {
        $data = $request->except('_token');
        $validateData = $this->validateData($data);
        if ($validateData->fails()) {
            return redirect()->back()->withErrors($validateData->errors())->withInput();
        }
        try {
            $this->adminRepository->updateAgent($id, $data);
            return redirect()->back()->with('success', 'Bạn đã cập nhật thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Cập nhật thất bại');
        }
    }

    public function validateData($data)
    {
        return Validator::make(
            $data,
            [
                'name' => ['required', 'max:255'],
                'phone_number' => 'required|regex:/[0-9]{10,11}/',
                'commission' => 'required|numeric|min:0',
                'staff_commission' => 'required_if:role,manager|nullable|numeric|min:0',
            ]
        );
    }
}
