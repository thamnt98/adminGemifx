<?php

namespace App\Http\Controllers\Admin\Agent;

use App\Http\Controllers\Controller;
use App\Repositories\AdminRepository;

class DetailController extends Controller
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

    public function main($id)
    {
        $agent = $this->adminRepository->getAgentDetail($id);
        if (is_null($agent->admin_id)) {
            $agent->role = 'manager';
        } else {
            $agent->role = 'staff';
        }
        $managers = $this->adminRepository->getManagerList();
        return view('admin.agent.detail', compact('agent', 'managers'));
    }
}
