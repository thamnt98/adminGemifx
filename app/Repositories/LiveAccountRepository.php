<?php

namespace App\Repositories;

use App\Helper\MT4Connect;
use App\Models\LiveAccount;
use \Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;
use Prettus\Repository\Contracts\RepositoryInterface;

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
}
