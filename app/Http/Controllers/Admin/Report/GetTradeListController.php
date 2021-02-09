<?php

namespace App\Http\Controllers\Admin\Report;

use App\Helper\MT4Connect;
use App\Http\Controllers\Controller;
use App\Repositories\LiveAccountRepository;

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

    public function main()
    {
        $logins = $this->liveAccountRepository->getLoginsByLoggedAdmin();
        $trades = $this->MT4Connect->getOpenedTrades($logins);
        dd($trades);
    }
}
