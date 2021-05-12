<?php

namespace App\Console\Commands;

use App\Helper\MT4Connect;
use App\Mail\SendReportEmail;
use App\Models\LiveAccount;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmailMarketing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send marketing email to customer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::get(['id', 'email', 'first_name', 'last_name']);
        $userHasMT4Account = $userNoMT4Account = [];
        foreach ($users  as $user){
            if(count($user->liveAccounts()->pluck('login'))){
                $userHasMT4Account[$user->email][0] = $user->first_name . ' ' . $user->last_name;
                $userHasMT4Account[$user->email][1] =  $user->liveAccounts()->pluck('login')->toArray();
            }else{
                $userNoMT4Account[] = [$user->email];
            }
        }
        $to = strtotime('now');
        $from = strtotime('-1 week');
        foreach($userHasMT4Account as $email => $value){
            $name = $value[0];
            $logins = $value[1];
            $orders = MT4Connect::reportByLogin($logins, $from, $to);
            $logins = implode(" | ", $logins);
            Mail::to($email)->send(new SendReportEmail($orders, $logins, $name));
        }
    }
}
