<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Settings\Repositories\ActionsRepo;
use App\Modules\User\Models\UserModel as User;
use App\Modules\User\Models\UsersHashes;
use App\Modules\User\Notifications\ResetPasswordNotification;
use App\Modules\User\Notifications\VerifyNotification;
use Carbon\Carbon;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Modules\User\Repositories\UserRepo;
use Illuminate\Support\Str;


class UserController extends Controller
{
    protected $repo;
    public function __construct()
    {
        $this->repo = new UserRepo();
    }

    public function login(Request $request)
    {
        if ($request->all())
        {
            $validator = Validator::make( $request->all(),[
                'login' => 'required',
                'password'=>'required',],
                                  ['required'=> 'Поле :attribute обязательно для заполнения',],
                                  ['login' => 'Логин', 'password'=>'Пароль']);
            if ($validator->fails())
            {
                return response()->json($validator->errors());
            }


            $user = (new User)->where('login', $request->login)->first();
            if (!$user){
                $user = (new User)->where('email', $request->login)->first();
                if (!$user){
                    return response()->json(["Пользователь с таким логином не найден в системе"]);
                }
            }



            if(Hash::check(request()->password.$user->salt, $user->password))
            {
                if (request()->remember){
                    $remember = true;
                }
                else{
                    $remember = false;
                }

                Auth::login($user, $remember);

                $request->session()->regenerate();
                ActionsRepo::new_field($user->id, 'Пользователь '.$user->login.' вошел в систему','','','', '', 'login');
                if (!$user->active){
                    return response()->json(['verify'=>'Вы должны подтвердить вашу электронную почту!']);
                }
                return response()->json(['redirect'=>route('manager.index')]);
            }

            return response()->json("Введенные данные не верны");
        }

    return view('User::login');
    }

    public function logout(Request $request){
        ActionsRepo::new_field(Auth::id(), 'Пользователь '.Auth::user()->login.' вышел из системы', '','','', '', 'logout');
        Auth::logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function register(Request $request)
    {
        if ($request->all()){
            $validator = Validator::make( $request->all(),[
                'login' => 'required|unique:users|max:50',
                'email' => 'required|email|unique:users|max:100',
                'password'=>'required|max:16',
                'password_confirm'=>'required|max:16',],
                  [
                      'required'=> 'Поле :attribute обязательно для заполнения',
                      'unique'=> 'Такой :attribute уже зарегистрирован в системе',
                      'max'=> 'Такой :attribute уже зарегистрирован в системе',
                      'email'=> 'Проверьте корректность :attribute',
                  ],
                    [
                        'login' => 'Логин', 'password'=>'Пароль', 'email'=> 'Email', 'password_confirm'=>'Подтвердите пароль'
                    ]);
            if ($validator->fails())
            {
                return response()->json($validator->errors());
            }

            if ($request['password'] !== $request['password_confirm'])
            {
                return response()->json(['Введенные пароли не совпадают']);
            }

            $salt = Str::random('10');
            $user = new User;
            $user->login = request()->login;
            $user->email = request()->email;
            $user->password = Hash::make(request()->password.$salt);
            $user->salt = $salt;
            $user->save();
            $user->notify(new VerifyNotification());

            return redirect()->to('/');
        }
        return view('User::register');
    }

    public function forgot(Request $request)
    {

        if ($request->all()){
            if (!$request->id){
                $validator = Validator::make( $request->all(),
                    ['email' => 'required|email|max:100',],
                    ['required'=> 'Поле :attribute обязательно для заполнения', 'email'=> 'Проверьте корректность :attribute', ],
                    ['email'=> 'Email']
                );
            }
            else{
                $validator = Validator::make( $request->all(),
                 [
                    'password'=>'required|max:16',
                    'password_confirm'=>'required|max:16',],
                  [
                      'required'=> 'Поле :attribute обязательно для заполнения',
                      'max'=> 'Такой :attribute уже зарегистрирован в системе',
                  ],
                    [
                        'login' => 'Логин', 'password'=>'Пароль', 'email'=> 'Email', 'password_confirm'=>'Подтвердите пароль'
                    ]);
            }
            if ($validator->fails())
            {
                return response()->json($validator->errors());
            }
            if ($request->id){
                $user = (new User)->where('id', request()->id)->first();
            }
            if (!$user->id) {
                $user = (new User)->where('email', request()->email)->first();
            }

            if (!$user->id){
                return response()->json('Нет пользователя с такими данными');
            }

            $user->notify(new ResetPasswordNotification());

            return redirect()->to('/');
        }
        return view('User::forgot');

    }
    public function forgot_change(Request $request){

        $user = (new User)->where('id', request()->id)->firstOrFail();
        if ($user->getKey() != $user->id) {
            return response()->json(['Ошибка проверки данных, запросите код верификации еще раз'], 401);
        }
        $hash_check = UsersHashes::where('user_id', $user->id)->first();
        if (!$hash_check or $hash_check->data != request()->hash){
            return response()->json(['Контрольная сумма не прошла проверку, запросите код верификации еще раз'], 401);
        }

        return view('User::change_password')->with(['id'=>$request->id]);
    }

    public function verify(Request $request)
    {
        $user = (new User)->where('id', $request->id)->firstOrFail();

        if ($user->active){
            return response()->json(['Ваш email уже подтвержден.']);
        }

        if ($user->getKey() != $user->id) {
            return response()->json(['Ошибка проверки данных, запросите код верификации еще раз'], 401);
        }

        $hash_check = UsersHashes::where('user_id', $user->id)->first();
        if (!$hash_check or $hash_check->data != request()->hash){
            return response()->json(['Контрольная сумма не прошла проверку, запросите код верификации еще раз'], 401);
        }

        $user->active = true;
        $user->email_verified_at = Carbon::now();
        $user->save();
        try {
            $hash_check->delete();
        } catch (Exception $e) {
        }

        return redirect()->to('/');
    }
}
