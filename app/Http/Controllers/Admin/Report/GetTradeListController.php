<?php

namespace App\Http\Controllers\Admin\Report;

use App\Helper\MT4Connect;
use App\Http\Controllers\Controller;
use App\Repositories\LiveAccountRepository;
use Illuminate\Http\Request;

class GetTradeListController extends Controller
{

    protected $MT4Connect;
    protected $liveAccountRepository;

    /**
     * GetTradeListController constructor.
     */
    public function __construct(MT4Connect $MT4Connect, LiveAccountRepository $liveAccountRepository)
    {
        $this->MT4Connect = $MT4Connect;
        $this->liveAccountRepository = $liveAccountRepository;
    }

    public function main(Request $request)
    {
        $closeTime = $request->close_time;
        $trades = [];
        if(!is_null($closeTime)){
            $data =  explode('-', $closeTime);
            $data['from'] = trim($data[0]);
            $data['to'] = trim($data[1]);
            $logins = $this->liveAccountRepository->getLoginsByLoggedAdmin();
            $trades = $this->MT4Connect->getOpenedTrades($logins, $data);
        }
        return view('admin.report.list', compact('closeTime', 'trades'));
    }
}
