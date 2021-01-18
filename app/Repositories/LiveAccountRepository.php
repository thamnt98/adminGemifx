<?php

namespace App\Repositories;

use App\Models\LiveAccount;
use App\Models\User;
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
}
