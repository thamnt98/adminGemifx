<?php

namespace App\Repositories;

use App\Models\User;
use \Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class AdminRepository
 * @package App\Repositories
 */
class UserRepository extends EloquentBaseRepository implements RepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function model()
    {
        return User::class;
    }
}
