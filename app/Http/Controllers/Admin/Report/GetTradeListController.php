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
        $lots = 0;
        $commission = 0;
        $trades = [];
        if (is_null($closeTime)) {
            $data['from'] = date('Y-m-01');
            $data['to'] = date('Y-m-d');
        } else {
            $data = explode('-', $closeTime);
            $data['from'] = trim($data[0]);
            $data['to'] = trim($data[1]);
        }
        $logins = $this->liveAccountRepository->getLoginsByLoggedAdmin();
        if (!empty($logins)) {
            $result = $this->MT4Connect->getOpenedTrades($logins, $data);
            $trades = $result[0];
            $lots = $result[1];
            $commission = $result[2];
        }
        return view('admin.report.list', compact('closeTime', 'trades', 'lots', 'commission'));
    }
}
