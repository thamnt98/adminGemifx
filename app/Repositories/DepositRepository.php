<?php

namespace App\Repositories;

use App\Models\Order;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;

/**
 * Class AdminRepository
 * @package App\Repositories
 */
class DepositRepository extends EloquentBaseRepository implements RepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function model()
    {
        return Order::class;
    }

    public function getDepositListBySearch($search)
    {
        $query = $this;
        if (isset($search['email']) && !is_null($search['email'])) {
            $query = $query->join('users', 'orders.user_id', '=', 'users.id')
                ->where('email', 'like', '%' . $search['email'] . '%');
        }
        return $query->paginate(20, ['orders.id', 'orders.user_id', 'orders.bank_name', 'orders.status',
            'orders.type', 'orders.amount_money', 'orders.created_at']);
    }
}
