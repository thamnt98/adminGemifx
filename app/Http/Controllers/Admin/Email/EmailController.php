<?php

namespace App\Http\Controllers\Admin\Email;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    protected  $userRepository;

    /**
     * EmailController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository  $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function main(){
        $path = '/home/gemimnhr/ib.gemifx.com/app/Mail/templates.json';
        $templates = file_get_contents($path);
        $templates = json_decode($templates);
        $customers = $this->userRepository->getCustomersHasMT4AccountOrNo();
        return view('admin.mail.marketing', compact('templates', 'customers'));
    }
}
