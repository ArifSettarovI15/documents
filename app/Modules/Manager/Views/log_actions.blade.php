@extends('BaseData.Views.managerlayout')
@section('title', 'Панель управления')

@section('content')
    <div class="container-fluid ">
        <div class="paging_data log-table mt-5">
            <div class="row text-center tw-table-header">
                <div class="col-md-1"><strong>ID</strong></div>
                <div class="col-md-5"><strong>Сообщение</strong></div>
                <div class="col-md-2"><strong>Пользователь</strong></div>
                <div class="col-md-2"><strong>Время</strong></div>
                <div class="col-md-1"><strong>Тип</strong></div>
                <div class="col-md-1"></div>
            </div>
            <div class="row text-center mt-2 mb-2">
                <div class="col-md-1"><input type="text" class="table-filter" name="log_id" data-type="search"/></div>
                <div class="col-md-5"><input type="text" class="table-filter" name="log_message" data-type="search"/></div>
                <div class="col-md-2"><select class="table-filter" name="log_user" data-type="filter">
                        @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->profile_name}} {{$user->profile_lastname}}</option>
                        @endforeach
                    </select></div>
                <div class="col-md-2"><input type="text" class="table-filter table-date_picker datepicker" name="log_time" data-type="less"/></div>
                <div class="col-md-1">
                    <select class="table-filter" name="log_type" data-type="filter">
                        <option value="">Все</option>
                        <option value="create">Создание</option>
                        <option value="change">Изменение</option>
                        <option value="delete">Удаление</option>
                        <option value="login">Логин</option>
                    </select>
                </div>
                <div class="col-md-1"></div>
            </div>
            <div class="table-data">
                @include('Manager.Views.logs_table')
            </div>
        </div>
        <div class="paging_links paging-data row">
            {{$actions->links('paging')}}
        </div>
    </div>
@endsection
