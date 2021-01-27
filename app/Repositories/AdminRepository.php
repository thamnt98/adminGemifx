<?php

namespace App\Repositories;

use \Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;
use Prettus\Repository\Contracts\RepositoryInterface;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

/**
 * Class AdminRepository
 * @package App\Repositories
 */
class AdminRepository extends EloquentBaseRepository implements RepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function model()
    {
        return Admin::class;
    }

    public function login($credentials)
    {
        return Auth::attempt($credentials);
    }

    public function getAgentList()
    {
        return $this->where('role', config('role.staff'))->paginate(20, ['name', 'email', 'phone_number', 'ib_id']);
    }
}
