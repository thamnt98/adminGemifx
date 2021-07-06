<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

/**
 * Class AdminRepository
 * @package App\Repositories
 */
class PermissionRepository extends EloquentBaseRepository implements RepositoryInterface
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Permission::class;
    }

    protected $roleRepository;

    public function __construct(\App\Repositories\RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param $data
     */
    public function createMultiplePermission($data)
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        DB::transaction(function () use ($data) {
            $this->insert($data);
        });
    }

    public function getAllPermission($mode)
    {
        dd($this->where('id'), 1)->get();
        if ($mode == 1){
            return $this->all();
        }
        $permissions = ['user.show', 'user.link', 'account.show', 'deposit.show', 'withdrawal.show', 'report.*'];
        if($mode == 2){
            $permissions[] = ['agent.link', 'agent.show'];
        }
        return $this->whereIn('name', $permissions)->get();
    }

    /**
     * @param $roleData
     * @return mixed
     */
    public function createOrUpdateSuperAdminRole($roleData)
    {
        return $this->roleRepository->firstOrCreate($roleData);
    }

    public function syncPermisionForUsers($users, $permissions)
    {
        foreach ($users as $user) {
            return $user->syncPermissions($permissions);
        }
    }

}
