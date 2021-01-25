<?php

namespace App\Repositories;

use \Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;
use Prettus\Repository\Contracts\RepositoryInterface;
use App\Models\WithdrawalFund;

/**
 * Class AdminRepository
 * @package App\Repositories
 */
class WithdrawalRepository extends EloquentBaseRepository implements RepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function model()
    {
        return WithdrawalFund::class;
    }

    public function getWithdrawalByLogin($login)
    {
        return $this->where('login', $login)->get();
    }

    public function getWithdrawalListBySearch($search){
        $query = $this;
        if (isset($search['email']) && !is_null($search['email'])) {
            $query = $query->join('users', 'withdrawal_funds.user_id', '=', 'users.id')
                ->where('users.email', 'like', '%' . $search['email'] . '%');
        }
        if (isset($search['login']) && !is_null($search['login'])) {
            $query = $query->where('login', 'like', '%' . $search['login'] . '%');
        }
        return $query->paginate(20, 'withdrawal_funds.*');
    }
}
