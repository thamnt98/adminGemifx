<?php

namespace App\Http\Controllers\Admin\Report;

use App\Helper\MT4Connect;
use App\Helper\MT5Helper;
use App\Http\Controllers\Controller;
use App\Repositories\LiveAccountRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetTradeListController extends Controller
{

    protected $mT5Helper;
    protected $liveAccountRepository;

    /**
     * GetTradeListController constructor.
     */
    public function __construct(MT5Helper $mT5Helper, LiveAccountRepository $liveAccountRepository)
    {
        $this->mT5Helper = $mT5Helper;
        $this->liveAccountRepository = $liveAccountRepository;
    }

    public function main(Request $request)
    {
        $closeTime = $request->close_time;
        $ibId = $request->ib_id;
        $lots = 0;
        $commission = 0;
        $trades = [];
        if (is_null($closeTime)) {
            $data['from'] = $data['to'] = date('Y-m-d');
        } else {
            $time = explode('-', $closeTime);
            $data['from'] = trim($time[0]);
            $data['to'] = trim($time[1]);
        }
        $logins = $this->liveAccountRepository->getLoginsByAdmin(Auth::user(), $ibId);
        if (!empty($logins)) {
            $data['startTm'] = date('Y-m-d H:i:s', strtotime($data['from'] . ' 00:00:00'));
            $data['EndTm']  = date('Y-m-d H:i:s', strtotime($data['to'] . ' 23:59:59'));
            $result = $this->mT5Helper->getOpenedTrades($logins, $data);
            $trades = $result[0];
            $lots = $result[1];
            $commission = $result[2];
        }
        return view('admin.report.list', compact('closeTime', 'ibId', 'trades', 'lots', 'commission'));
    }
}
