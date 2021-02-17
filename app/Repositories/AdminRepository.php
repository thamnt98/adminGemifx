<?php

namespace App\Repositories;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;

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

    public function isActive($email)
    {
        return $this->where('email', $email)->first()->status;
    }

    public function login($credentials)
    {
        return Auth::attempt($credentials);
    }

    public function getAgentList()
    {
        $query = $this->where('role', config('role.staff'));
        $user = Auth::user();
        if ($user->role == config('role.staff')) {
            $query = $query->where('admin_id', $user->id);
        }
        return $query->paginate(20, ['id', 'name', 'email', 'phone_number', 'ib_id', 'status', 'admin_id']);
    }

    public function activeAgent($id, $status)
    {
        return $this->update(['status' => $status], $id);
    }

    public function getAgentDetail($id){
        return $this->find($id);
    }

    public function updateAgent($id, $data){
        return $this->update($data, $id);
    }
}
