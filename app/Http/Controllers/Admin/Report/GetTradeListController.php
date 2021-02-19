<?php

namespace App\Http\Controllers\Admin\Report;

use App\Helper\MT4Connect;
use App\Http\Controllers\Controller;
use App\Repositories\LiveAccountRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $ibId = $request->ib_id;
        $lots = 0;
        $commission = 0;
        $trades = [];
        if (!isset($closeTime)) {
            $data['from'] = date('Y-m-01');
            $data['to'] = date('Y-m-d');
        } else {
            $time = explode('-', $closeTime);
            $data['from'] = trim($time[0]);
            $data['to'] = trim($time[1]);
        }
        $logins = $this->liveAccountRepository->getLoginsByAdmin(Auth::user(), $ibId);
        if (!empty($logins)) {
            $from = strtotime($data['from'] . ' 00:00:00');
            $to = strtotime($data['to'] . ' 23:59:59');
            $result = $this->MT4Connect->getOpenedTrades($logins, $from, $to);
            $trades = $result[0];
            $lots = $result[1];
            $commission = $result[2];
        }
        return view('admin.report.list', compact('closeTime', 'ibId', 'trades', 'lots', 'commission'));
    }
}
