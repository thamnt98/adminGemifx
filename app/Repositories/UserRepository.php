<?php

namespace App\Repositories;

use App\Helper\MT4Connect;
use App\Models\LiveAccount;
use App\Models\User;
use \Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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

    public function updateUser($id, $data)
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
        $user = $this->find(
            $id,
            ['first_name', 'last_name', 'phone_number', 'zip_code', 'city', 'state', 'address', 'country']
        )->toArray();
        $liveAccountData = [];
        foreach ($user as $key => $value) {
            if ($value != $data[$key]) {
                $liveAccountData[$key] = $data[$key];
            }
        }
        if (($data['first_name'] . ' ' . $data['last_name']) != ($user['first_name'] . ' ' . $user['last_name'])) {
            $liveAccountData['name'] = $data['first_name'] . ' ' . $data['last_name'];
            unset($liveAccountData['first_name']);
            unset($liveAccountData['last_name']);
        }
        if (isset($liveAccountData['zip_code'])) {
            $liveAccountData['zipcode'] = $liveAccountData['zip_code'];
            unset($liveAccountData['zip_code']);
        }
        if (isset($liveAccountData['phone_number'])) {
            $liveAccountData['phone'] = $liveAccountData['phone_number'];
            unset($liveAccountData['phone_number']);
        }
        try {
            DB::beginTransaction();
            $this->update($data, $id);
            if (!empty($liveAccountData)) {
                $logins = LiveAccount::where('user_id', $id)->pluck('login')->toArray();
                $input = $liveAccountData;
                foreach ($logins as $login) {
                    $input['login'] = $login;
                    MT4Connect::updateLiveAccount($input);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Cập nhật thất bại');
        }
    }

    private function uploadFile($file)
    {

        $name = time() . '.' . $file->getClientOriginalName();
        Storage::disk('public')->put($name, file_get_contents($file));
        return Storage::disk('public')->url($name);
    }
}
