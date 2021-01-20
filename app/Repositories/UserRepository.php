<?php

namespace App\Repositories;

use App\Models\LiveAccount;
use App\Models\User;
use \Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\Storage;

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

    public function getUserBySelect($select)
    {
        return $this->all($select);
    }

    public function update($id, $data)
    {
        if (isset($data['copy_of_id'])) {
            $data['copy_of_id'] = $this->uploadFile($data['copy_of_id']);
        }
        if (isset($data['addtional_file'])) {
            $data['addtional_file'] = $this->uploadFile($data['addtional_file']);
        }
        if (isset($data['proof_of_address'])) {
            $data['proof_of_address'] = $this->uploadFile($data['proof_of_address']);
        }
        $this->update($id, $data);
    }

    private function uploadFile($file)
    {
       
        $name = time() . '.' . $file->getClientOriginalName();
        Storage::disk('public')->put($name, file_get_contents($file));
        return  Storage::disk('public')->url($name);
    }
}
