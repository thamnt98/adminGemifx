<?php

namespace App\Http\Controllers\Admin\Email;

use App\Http\Controllers\Controller;
use App\Mail\EmailMarketing;
use App\Mail\OpenLiveAccountSuccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SendEmailMarketingController extends Controller
{
    public function main(Request $request){
        $data = $request->except('_token');
        $validateData = $this->validateData($data);
        if ($validateData->fails()) {
            return redirect()->back()->withErrors($validateData->errors())->withInput();
        }
        try {
            Mail::to($data['customers'])->send(new EmailMarketing($data['template_email'], $data['title']));
            return redirect()->back()->with('success', 'Gửi email thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gửi email thất bại');
        }
    }

    public function validateData($data)
    {
        return Validator::make(
            $data,
            [
                'template_email' => 'required',
                'title' => 'required|max:255',
                'customers' => 'required|array',
            ]
        );
    }
}
