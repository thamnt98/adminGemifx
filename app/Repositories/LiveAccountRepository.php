<?php

namespace App\Repositories;

use App\Helper\MT4Connect;
use App\Models\Admin;
use App\Models\LiveAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;

/**
 * Class AdminRepository
 * @package App\Repositories
 */
class LiveAccountRepository extends EloquentBaseRepository implements RepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function model()
    {
        return LiveAccount::class;
    }

    public function deleteLiveAccountByUserId($userId)
    {
        $logins = $this->where('user_id', $userId)->pluck('login')->toArray();
        $message = MT4Connect::deleteMultiLiveAccount($logins);
        $this->where('user_id', $userId)->delete();
        return $message;
    }

    public function deleteByLogin($login)
    {
        $this->where('login', $login)->delete();
    }

    public function openLiveAccount($user, $data)
    {
        $data['name'] = $user->full_name;
        $data['phone'] = 'xxxxxx' . substr($user['phone'], -4);
        $data['zipcode'] = $user->zip_code;
        $data['city'] = $user->city;
        $data['state'] = $user->state;
        $data['address'] = $user->address;
        $data['country'] = $user->country;
        $data['password'] = Str::random(7);
        $data['agent'] = $data['ib_id'];
        $data['login'] = MT4Connect::openLiveAccount($data);
        if (strlen($data['login']) == 10) {
            $data['phone_number'] = $user->phone_number;
            $data['user_id'] = $user->id;
            $this->create($data);
            return [
                'login'    => $data['login'],
                'password' => $data['password'],
            ];
        }
        return $data['login'];
    }

    public function updateLiveAccount($id, $data)
    {
        $data['login'] = $this->find($id)->login;
        $result = MT4Connect::updateLiveAccount($data);
        if (is_null($result)) {
            $data['phone_number'] = $data['phone'];
            $this->update($data, $id);
            return null;
        }
        return $result;
    }

    public function getAccountListBySearch($search)
    {
        $query = $this;
        $user = Auth::user();
        if ($user->role == config('role.staff')) {
            $ibIds = [$user->ib_id];
            if(is_null($user->admin_id)){
                $ibIdsOfStaff = Admin::where('admin_id', $user->id)->pluck('ib_id')->toArray();
                $ibIds = array_merge($ibIds, $ibIdsOfStaff);
            }
            $query = $query->whereIn('live_accounts.ib_id', $ibIds);
        }
        if (!empty($search)) {
            if (isset($search['login']) && !is_null($search['login'])) {
                $query = $query->where('login', 'like', '%' . $search['login'] . '%');
            }
            if (isset($search['ib_id']) && !is_null($search['ib_id'])) {
                $query = $query->where('ib_id', 'like', '%' . $search['ib_id'] . '%');
            }
            if (isset($search['email']) && !is_null($search['email'])) {
                $query = $query
                    ->join('users', 'live_accounts.user_id', '=', 'users.id')
                    ->where('users.email', 'like', '%' . $search['email'] . '%');
            }
        }
        return $query->orderBy('live_accounts.created_at', 'desc')->paginate(20, [
            'live_accounts.id',
            'live_accounts.login',
            'live_accounts.group',
            'live_accounts.leverage',
            'live_accounts.ib_id',
            'live_accounts.user_id',
        ]);
    }

    public function getLoginsByLoggedAdmin()
    {
        $user = Auth::user();
        if ($user->role == config('role.staff')) {
            $logins = $this->where('ib_id', $user->ib_id)->pluck('login')->toArray();
            $result = array_fill_keys($logins, $user->commission);
            if (is_null($user->admin_id)) {
                $ibIds = Admin::where('admin_id', $user->id)->pluck('ib_id')->toArray();
                $logins = $this->whereIn('ib_id', $ibIds)->pluck('login')->toArray();
                $result = array_merge($result, array_fill_keys($logins, $user->staff_commission));
            }
        }else{
            $logins = $this->pluck('login')->toArray();
            $result = array_fill_keys($logins, $user->staff_commission);
        }
        return $result;
    }
}
