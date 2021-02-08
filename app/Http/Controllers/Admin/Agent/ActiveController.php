<?php

namespace App\Http\Controllers\Admin\Agent;

use App\Http\Controllers\Controller;
use App\Repositories\AdminRepository;

class ActiveController extends Controller
{

    /**
     * @var AdminRepository
     */
    private $adminRepository;

    /**
     * LiveListController constructor.
     * @param AdminRepository $adminRepository
     */
    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function main($id)
    {
        $result = $this->adminRepository->activeAgent($id);
        if ($result) {
            return redirect()->back()->with('success', 'Bạn đã active thành công');
        } else {
            return redirect()->back()->with('error', 'Active thất bại ');
        }
    }
}
