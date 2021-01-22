<?php

namespace App\Repositories;

use App\Helper\MT4Connect;
use App\Models\LiveAccount;
use Illuminate\Support\Facades\Auth;
use \Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Str;

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
        $data['login'] = MT4Connect::openLiveAccount($data);
        if (strlen($data['login']) == 10) {
            $data['phone_number'] = $user->phone_number;
            $data['user_id'] = $user->id;
            $this->create($data);
            return [
                'login' => $data['login'],
                'password' => $data['password']
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
}
