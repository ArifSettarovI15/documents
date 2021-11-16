@extends('BaseData.Views.managerlayout')
@section('title', 'Пользователи')
@include('components.bread-crumbs', with($breadcrumbs))
@section('content')
    <div class="container">
        <form class="mt-lg-5 h-100"
              @isset($user->id)
              action="{{route('manager.users.update', $user->id)}}"
              @else
              action="{{route('manager.users.store')}}"
            @endisset >
            <fieldset class="mb-5">
                @isset($info->service_id)
                    <legend>Пользователь - @if($user->profile_name and $user->profile_lastname)
                            {{$user->profile_name}} {{$user->profile_lastname}}| {{$user->profile_login}}
                        @else
                            {{$user->profile_login}}
                        @endif</legend>
                @else
                    <legend>Создание нового пользователя</legend>
                @endisset
            </fieldset>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="login" class="required">Логин пользователя</label>
                    <input type="text" name="login" class="form-control" id="login"
                           placeholder="user_123" required
                           value="@isset($user->login){{$user->login}}@endisset">
                </div>
                <div class="form-group col-md-6">
                    <label for="password" class="required">Пароль пользователя</label>
                    <div class="input-group">
                        <input type="password" class="form-control input-lg"
                               name="password" id="password" rel="gp" data-size="16"
                               data-character-set="a-z,A-Z,0-9" value="@isset($user){{$user->password}}@endisset"
                               required>
                        <span class="input-group-btn"><button type="button" class="btn btn-dark getNewPass"><span
                                    class="fa fa-sync-alt"></span></button></span>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="name" class="required">Имя</label>
                    <input type="text" name="profile_name" id="name" class="form-control" required
                           value="@isset($user){{$user->profile_name}}@endisset">
                </div>
                <div class="form-group col-md-3">
                    <label for="lastname" class="required">Фамилия</label>
                    <input type="text" name="profile_lastname" id="lastname" class="form-control" required
                           value="@isset($user){{$user->profile_lastname}}@endisset">
                </div>
                <div class="form-group col-md-3">
                    <label for="email" class="required">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required
                           value="@isset($user){{$user->email}}@endisset">
                </div>
                <div class="form-group col-md-3">
                    <label for="role" class="required">Роль</label>
                    <select name="role_id" id="role" class="form-control" required>
                        <option value="1" @if(isset($user) && $user->role_id == 1) selected @endif>Пользователь
                        </option>
                        <option value="2" @if(isset($user) && $user->role_id == 2) selected @endif>Модератор</option>
                        <option value="3" @if(isset($user) && $user->role_id == 3) selected @endif>Администратор
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Создать</button>
            </div>
        </form>
    </div>
@endsection
