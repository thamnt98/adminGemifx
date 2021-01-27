<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreateController extends Controller
{
    public function main()
    {
        $ibId = Auth::user()->ib_id;
        return view('admin.user.create', compact('ibId'));
    }
}
