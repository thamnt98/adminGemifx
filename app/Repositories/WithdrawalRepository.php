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
}
