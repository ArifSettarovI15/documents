<?php

namespace App\Modules\User\Repositories;

use App\Modules\User\Models\UserModel;
use App\Modules\User\Models\UserProfileModel;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRepo extends BaseRepository
{


    /**
     * UserRepo constructor.
     * @var UserModel $model
     */
    public function __construct()
    {
        $this->model = new UserModel();
    }

    /**
     * @param $request
     * @return Model|Builder
     */
    public function new_item($request)
    {
        $salt = Str::random('10');
        $request->merge(['password' => Hash::make($request['password'] . $salt),
                         'salt' => $salt]);

        $user = $this->model->create($request->all());

        $request->merge(['profile_user_id'=>$user->id]);

        (new UserProfileModel)->create($request->all());

        return $user;
    }
    public function update_item($id, $request): bool
    {
        $user = $this->model->findOrFail($id);
        $user->fill($request->all())->save();

        $profile = (new UserProfileModel)->find($id, 'profile_user_id');
        if ($profile){
            $profile->fill($request->all())->save();
        }
        else
        {
            $request->merge(['profile_user_id'=>$id]);
            (new UserProfileModel)->create($request->all());
        }

        return true;
    }

    public function get_item($id): Model
    {
        return $this->model->where('id',$id)->join('users_profile', 'users_profile.profile_user_id','=','users.id')->first();
    }


}
