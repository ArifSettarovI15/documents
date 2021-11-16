@extends('BaseData.Views.managerlayout')
@section('title', 'Пользователи')

@include('components.bread-crumbs', with($breadcrumbs))

@section('content')
    <div class="container-fluid" >

        <a href="{{route('manager.users.create')}}" class="btn btn-success float-right m-5" >Новый пользователь</a>

        <table class="table table-striped" style="margin-top: 100px;">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Статус</th>
                <th scope="col">Имя</th>
                <th scope="col">Логин</th>
                <th scope="col">Роль</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <th scope="row" class="pl-5">{{$user->id}}</th>
                    <td>
                        <input type="checkbox" data-toggle="toggle" class="js_status_change"
                               data-id="{{$user->id}}" data-current="{{$user->active}}"
                               data-field="active"
                               data-url = "{{route('manager.users.status')}}"
                               data-on="Акт" data-off="Неакт"
                               data-size="sm" data-onstyle="info"
                               @if($user->active) checked @endif>
                    </td>
                    <td>{{$user->profile_name}} {{$user->profile_lastname}}</td>
                    <td>{{$user->login}}</td>

                    <td>
                        @switch($user->role_id)
                            @case(1)Пользователь @break
                            @case(2)Модератор @break
                            @case(3)Администратор @break
                        @endswitch
                    </td>
                    <td>
                        <a href="{{route('manager.users.show', $user->id)}}">
                            <i class="fas fa-pen"></i>
                        </a>
                        <a href="{{route('manager.users.destroy', $user->id)}}" class="ml-2 js_ajax_delete">
                            <i class="fas fa-trash-alt icon_danger"></i>
                        </a>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 100px 0;">
                        <h2>Нет данных для вывода</h2>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="mt-5">
            {{$users->links('paging')}}
        </div>
    </div>
@endsection
