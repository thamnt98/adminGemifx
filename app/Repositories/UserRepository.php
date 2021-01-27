<?php

namespace App\Repositories;

use App\Helper\MT4Connect;
use App\Models\LiveAccount;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;

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
        $user = Auth::user();
        if ($user->role == config('role.staff')) {
            return $this->where('users.ib_id', $user->ib_id)->get($select);
        }
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
            ['first_name', 'last_name', 'phone_number', 'zip_code', 'city', 'state', 'address', 'country', 'ib_id']
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
                if(isset($liveAccountData['ib_id'])){
                    LiveAccount::where('user_id', $id)->update(['ib_id' => $liveAccountData['ib_id']]);
                    $input['agent'] = $liveAccountData['ib_id'];
                }
                foreach ($logins as $login) {
                    $input['login'] = $login;
                    MT4Connect::updateLiveAccount($input);
                }
            }
            DB::commit();
        } catch (Exception $e) {
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

    public function getUserListBySearch($search)
    {
        $query = $this;
        if (!empty($search)) {
            if (isset($search['email']) && !is_null($search['email'])) {
                $query = $query->where('email', 'like', '%' . $search['email'] . '%');
            }
            if (isset($search['login']) && !is_null($search['login'])) {
                $query = $query
                    ->join('live_accounts', 'users.id', '=', 'live_accounts.user_id')
                    ->where('live_accounts.login', 'like', '%' . $search['login'] . '%')
                    ->distinct('live_accounts.user_id');
            }
        }
        $user = Auth::user();
        if ($user->role == config('role.staff')) {
            $query = $query->where('users.ib_id', $user->ib_id);
        }
        return $query->orderBy('users.created_at', 'desc')->paginate(20, [
            'users.last_name',
            'users.first_name',
            'users.id',
            'users.email',
            'users.phone_number',
            'users.copy_of_id',
            'users.address',
            'users.country',
        ]);
    }
}
